<?php

namespace App\Http\Controllers\Donations;

use App\Eloquent\Donation;
use App\Eloquent\Payment;
use App\Enums\PaymentStatus;
use App\Services\Ecpay;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request as HTTPRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    public function period(string $uuid, HTTPRequest $reqs, Ecpay $ecpay): string
    {
        /** @var \App\Eloquent\Donation $donation */
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
        $checkMacValue = $ecpay->generateCheckSum($reqs->all());
        if ($checkMacValue !== $reqs->input('CheckMacValue')) {
            Log::critical('Invalid CheckMacValue', ['requests' => $reqs->all(), 'mac' => $checkMacValue]);
            abort(500, 'Invalid CheckMacValue');
        }

        // create new payment
        $payment = Payment::createFromDonation($donation);
        $payment->status = $reqs->input('RtnCode') === '1' ? PaymentStatus::PAID() : PaymentStatus::FAILED();
        $payment->save();

        return '1|OK';
    }

    public function first(string $uuid, HTTPRequest $reqs, Ecpay $ecpay): string
    {
        /** @var \App\Eloquent\Donation $donation */
        $donation = Donation::query()->where('uuid', $uuid)->firstOrFail();

        // check mac value
        $checkMacValue = $ecpay->generateCheckSum($reqs->all());
        if ($checkMacValue !== $reqs->input('CheckMacValue')) {
            Log::critical('Invalid CheckMacValue', ['requests' => $reqs->all(), 'mac' => $checkMacValue]);
            abort(500, 'Invalid CheckMacValue');
        }

        /** @var \App\Eloquent\Payment $payment */
        $payment = $donation->payments()->orderBy('id')->firstOrFail();
        $payment->status = $reqs->input('RtnCode') === '1' ? PaymentStatus::PAID() : PaymentStatus::FAILED();
        $payment->save();

        return '1|OK';
    }
}
