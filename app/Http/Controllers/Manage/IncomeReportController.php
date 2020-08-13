<?php

namespace App\Http\Controllers\Manage;

use App\Enums\DonationStatus;
use App\Enums\DonationType;
use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class IncomeReportController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var \Carbon\Carbon */
        $baseDate = now()
            ->when($request->has('year'), fn ($d) => $d->setYear($request->get('year')))
            ->when($request->has('month'), fn ($d) => $d->setMonth($request->get('month')))
            ->endOfMonth();

        $expires = $baseDate->startOfMonth()->subMonths(3);

        $donations = Donation
            ::where('type', DonationType::MONTHLY())
            ->where('status', '<>', DonationStatus::CREATED())
            ->where('created_at', '<=', $baseDate)
            ->with([
                'latest_payment' => fn ($q) => $q->whereBetween('created_at', [$expires, $baseDate]),
            ])
            ->get()
            ->filter(fn (Donation $d) => optional($d->getLastPaymentTime())->gt($expires))
            ->sortBy('latest_payment.created_at');

        return view('manage.income-report', [
            'baseDate' => $baseDate,
            'donations' => $donations,
        ]);
    }
}
