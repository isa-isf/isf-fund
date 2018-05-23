<?php

namespace App\Http\Controllers;

use App\Eloquent\Donation;
use App\Eloquent\Payment;
use Illuminate\Http\Request;

class DonationsController extends Controller
{
    public function store(Request $request)
    {
        // @todo: form validation
        $this->validate($request, []);

        $profile = $request->input('profile');
        $payment = $request->input('payment');

        $donation = new Donation;
        $donation->name = $profile['name'] ?? '';
        $donation->phone = $profile['phone'] ?? '';
        $donation->email = $profile['email'] ?? '';
        $donation->address = $profile['address'] ?? '';
        $donation->type = $payment['type'] ?? 'monthly';
        $donation->amount = $payment['amount'] ?: $payment['custom_amount'];
        $donation->message = $payment['message'] ?? '';
        $donation->save();

        // create first payment
        $payment = Payment::createFromDonation($donation);

        // @todo: pay out
    }
}
