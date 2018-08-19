<?php

namespace App\Http\Controllers\Donations;

use App\Eloquent\Donation;
use App\Eloquent\Payment;
use App\Enums\PaymentStatus;
use App\Services\Ecpay;
use Illuminate\Http\Request as HTTPRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    public function period(string $uuid, HTTPRequest $reqs, Ecpay $ecpay): string
    {
        /** @var \App\Eloquent\Donation $donation */
        $donation = Donation::query()->where('uuid', $uuid)->firstOrFail();

        // check mac value
        $checkMacValue = $ecpay->generateCheckSum($reqs->all());
        if ($checkMacValue !== $reqs->input('CheckMacValue')) {
            Log::critical('Invalid CheckMacValue', ['requests' => $reqs->all(), 'mac' => $checkMacValue]);
            abort(500, 'Invalid CheckMacValue');
        }

        // create new payment
        $payment = Payment::createFromDonation($donation);
        $payment->status = $reqs->input('RtnCode') === 1 ? PaymentStatus::PAID() : PaymentStatus::FAILED();
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
        $payment->status = $reqs->input('RtnCode') === 1 ? PaymentStatus::PAID() : PaymentStatus::FAILED();
        $payment->save();

        return '1|OK';
    }
}
