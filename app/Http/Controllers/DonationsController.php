<?php

namespace App\Http\Controllers;

use App\Eloquent\Donation;
use App\Eloquent\Payment;
use App\Enums\DonationType;
use Illuminate\Http\Request;

class DonationsController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'profile.name' => 'required',
            'profile.phone' => 'required',
            'profile.email' => 'required|email',
            'profile.address' => 'required',
            'payment.type' => 'required|in:' . implode(',', DonationType::values()),
            'payment.amount' => 'required|integer',
            'payment.custom_amount' => 'required_if:amount,0',
        ]);

        $profile = $request->input('profile');
        $payment = $request->input('payment');

        $donation = new Donation;
        $donation->name = $profile['name'] ?? '';
        $donation->phone = $profile['phone'] ?? '';
        $donation->email = $profile['email'] ?? '';
        $donation->address = $profile['address'] ?? '';
        $donation->type = new DonationType($payment['type'] ?? 'monthly');
        $donation->amount = $payment['amount'] ?: $payment['custom_amount'];
        $donation->message = $payment['message'] ?? '';
        $donation->save();

        // create first payment
        $payment = Payment::createFromDonation($donation);

        // @todo: pay out
    }
}
