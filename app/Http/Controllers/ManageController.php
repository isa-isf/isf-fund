<?php

namespace App\Http\Controllers;

use App\Enums\DonationStatus;
use App\Enums\DonationType;
use App\Enums\PaymentStatus;
use App\Models\Donation;
use App\Models\Payment;

class ManageController
{
    public function index()
    {
        $startOfMonth = now()->startOfMonth();

        $recentOneTime = Donation
            ::query()
            ->where('status', DonationStatus::ACTIVE())
            ->where('type', DonationType::ONE_TIME())
            ->where('created_at', '>=', now()->subDays(30))
            ->latest()
            ->get();

        $donations = Donation
            ::query()
            ->where('status', DonationStatus::ACTIVE())
            ->where('type', DonationType::MONTHLY())
            ->latest()
            ->with(['latest_payment'])
            ->get()
            ->sortBy('latest_payment.updated_at');

        $analytics = [];
        $analytics['monthly'] = $donations->sum('amount');
        $analytics['monthly_collected_count'] = $donations->filter(fn ($d) => optional($d->getLastPaymentTime())->isCurrentMonth())->count();
        $analytics['monthly_collected_amount'] = $donations->filter(fn ($d) => optional($d->getLastPaymentTime())->isCurrentMonth())->sum('amount');
        $analytics['total_collected'] = Payment
            ::query()
            ->where('status', PaymentStatus::PAID())
            ->where('created_at', '>=', $startOfMonth)
            ->sum('amount');
        $analytics['forecast'] = $analytics['monthly'];
        $analytics['forecast'] += Payment
            ::query()
            ->where('payments.status', PaymentStatus::PAID())
            ->where('payments.created_at', '>=', $startOfMonth)
            ->where('donations.type', DonationType::ONE_TIME())
            ->join('donations', 'donations.id', 'payments.donation_id')
            ->sum('payments.amount');

        return view('manage.index', [
            'analytics' => $analytics,
            'recentOneTime' => $recentOneTime,
            'donations' => $donations
        ]);
    }
}
