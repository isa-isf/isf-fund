<?php

namespace App\Http\Controllers\Manage;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __invoke(Request $request): View
    {
        $fromDate = now()->startOfMonth()->subMonths(2);
        $toDate = now()->addMonth();

        if ($request->input('from.year') && $request->input('from.month')) {
            $fromDate = $fromDate
                ->setYear($request->input('from.year'))
                ->setMonth($request->input('from.month'));
        }
        if ($request->input('to.year') && $request->input('to.month')) {
            $toDate = $toDate
                ->setYear($request->input('to.year'))
                ->setMonth($request->input('to.month'))
                ->addMonth()
                ->startOfMonth();
        }

        $donationsId = Payment
            ::whereBetween('created_at', [$fromDate, $toDate])
            ->where('status', PaymentStatus::PAID()->__toString())
            ->get('donation_id')
            ->pluck('donation_id');

        $donations = Donation::whereIn('id', $donationsId)->get();

        return view('manage.address', [
            'donations' => $donations,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }
}
