<?php

namespace App\Http\Controllers\Donations;

use App\Enums\DonationStatus;
use App\Enums\DonationType;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationLatestPaymentRelationship;
use App\Models\Payment;
use App\Services\Ecpay;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request as HTTPRequest;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    public function period(string $uuid, HTTPRequest $reqs, Ecpay $ecpay): string
    {
        /** @var \App\Models\Donation $donation */
        $donation = Donation
            ::query()
            ->select('donations.*')
            ->when(\strlen($uuid) === 16, function (Builder $query) use ($uuid) {
                $id = ltrim(substr($uuid, 12, 4), '0');
                $query->join('payments', 'payments.donation_id', 'donations.id');
                $query->where('payments.id', $id);
            }, function (Builder $query) use ($uuid) {
                $query->where('uuid', $uuid);
            })->firstOrFail();

        // check mac value
        $algorithm = \strlen($reqs->input('CheckMacValue')) === 32 ? Ecpay::ENCRYPT_TYPE_MD5 : Ecpay::ENCRYPT_TYPE_SHA256;
        $checkMacValue = $ecpay->generateCheckSum($reqs->all(), $algorithm);
        if ($checkMacValue !== $reqs->input('CheckMacValue')) {
            Log::critical('Invalid CheckMacValue', ['requests' => $reqs->all(), 'mac' => $checkMacValue]);
            abort(500, 'Invalid CheckMacValue');
        }

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

    public function first(string $uuid, HTTPRequest $reqs, Ecpay $ecpay): string
    {
        /** @var \App\Models\Donation $donation */
        $donation = Donation::query()->where('uuid', $uuid)->firstOrFail();

        // check mac value
        $algorithm = \strlen($reqs->input('CheckMacValue')) === 32 ? Ecpay::ENCRYPT_TYPE_MD5 : Ecpay::ENCRYPT_TYPE_SHA256;
        $checkMacValue = $ecpay->generateCheckSum($reqs->all(), $algorithm);
        if ($checkMacValue !== $reqs->input('CheckMacValue')) {
            Log::critical('Invalid CheckMacValue', ['requests' => $reqs->all(), 'mac' => $checkMacValue]);
            abort(500, 'Invalid CheckMacValue');
        }

        /** @var \App\Models\Payment $payment */
        $payment = $donation->payments()->orderByDesc('id')->firstOrFail();
        $payment->gateway_id = $reqs->input('TradeNo');
        $payment->status = $reqs->input('RtnCode') === '1' ? PaymentStatus::PAID() : PaymentStatus::FAILED();
        $payment->save();

        switch (true) {
            case DonationType::ONE_TIME()->equals($donation->type):
                $donation->status = DonationStatus::INACTIVE();
                break;
            case DonationType::MONTHLY()->equals($donation->type):
            default:
                $donation->status = DonationStatus::ACTIVE();
                break;
        }
        if (PaymentStatus::PAID()->equals($payment->status)) {
            $donation->latest_payment_id = $payment->id;
        }
        $donation->save();

        return '1|OK';
    }
}
