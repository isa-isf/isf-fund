<?php

namespace App\Http\Controllers\Donations;

use App\Enums\DonationStatus;
use App\Enums\DonationType;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Payment;
use App\Services\Ecpay;
use Illuminate\Http\Request as HTTPRequest;
use Illuminate\Support\Facades\Log;

final class CallbackController extends Controller
{
    public function __construct(
        private Ecpay $ecpay
    ) {
    }

    public function period(string $uuid, HTTPRequest $reqs): string
    {
        $donation = $this->fetchAndVerify($uuid, $reqs);

        // create new payment
        $payment = Payment::createFromDonation($donation);
        $payment->status = $reqs->input('RtnCode') === '1' ? PaymentStatus::PAID() : PaymentStatus::FAILED();
        $payment->gateway_id = $reqs->input('TradeNo');
        $payment->save();

        $donation->status = DonationStatus::ACTIVE();
        if (PaymentStatus::PAID()->equals($payment->status)) {
            $donation->latest_payment_id = $payment->id;
        }
        $donation->save();

        return '1|OK';
    }

    public function first(string $uuid, HTTPRequest $reqs): string
    {
        $donation = $this->fetchAndVerify($uuid, $reqs);

        /** @var \App\Models\Payment $payment */
        $payment = $donation->payments()->orderByDesc('id')->firstOrFail();
        $payment->gateway_id = $reqs->input('TradeNo');
        $payment->status = $reqs->input('RtnCode') === '1' ? PaymentStatus::PAID() : PaymentStatus::FAILED();
        $payment->save();

        $donation->status = match (true) {
            DonationType::ONE_TIME()->equals($donation->type) => DonationStatus::INACTIVE(),
            default => DonationStatus::ACTIVE(),
        };
        if (PaymentStatus::PAID()->equals($payment->status)) {
            $donation->latest_payment_id = $payment->id;
        }

        [$paymentMethod, ] = explode('_', $reqs->input('PaymentType'), 2);
        $donation->payment_method = match ($paymentMethod) {
            'Credit' => PaymentMethod::CreditCard()->__toString(),
            'ATM' => PaymentMethod::ATM()->__toString(),
            'CVS', 'BARCODE' => PaymentMethod::CVS()->__toString(),
        };

        $donation->save();

        return '1|OK';
    }

    public function paymentInfo(string $uuid, HTTPRequest $request): string
    {
        $donation = $this->fetchAndVerify($uuid, $request);

        [$paymentMethod, ] = explode('_', $request->input('PaymentType'), 2);

        $donation->payment_method = match ($paymentMethod) {
            'ATM' => PaymentMethod::ATM()->__toString(),
            'CVS', 'BARCODE' => PaymentMethod::CVS()->__toString(),
        };

        $donation->payment_info = match ($paymentMethod) {
            'ATM' => $request->only(['BankCode', 'vAccount', 'ExpireDate']),
            'CVS', 'BARCODE' => $request->only(['PaymentNo', 'ExpireDate', 'Barcode1', 'Barcode2', 'Barcode3']),
        };

        $donation->save();

        return '1|OK';
    }

    private function fetchAndVerify(string $uuid, HTTPRequest $request): Donation
    {
        $donation = Donation::query()->where('uuid', $uuid)->firstOrFail();

        // check mac value
        $algorithm = \strlen($request->input('CheckMacValue')) === 32 ? Ecpay::ENCRYPT_TYPE_MD5 : Ecpay::ENCRYPT_TYPE_SHA256;
        $checkMacValue = $this->ecpay->generateCheckSum($request->all(), $algorithm);
        if ($checkMacValue !== $request->input('CheckMacValue')) {
            Log::critical('Invalid CheckMacValue', ['requests' => $request->all(), 'mac' => $checkMacValue]);
            abort(500, 'Invalid CheckMacValue');
        }

        return $donation;
    }
}
