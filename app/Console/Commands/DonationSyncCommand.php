<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatus;
use App\Models\Donation;
use App\Models\Payment;
use App\Services\Ecpay;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

final class DonationSyncCommand extends Command
{
    protected $signature = 'donation:sync {donationHashId}';
    protected $description = 'Sync monthly payment data from ecpay.';

    public function __construct(
        private Ecpay $ecpay
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        /** @var Donation $donation */
        $donation = Donation
            ::with([
                'payments' => fn ($query) => $query->latest()->whereNotNull('gateway_id'),
            ])
            ->whereHashidKey($this->argument('donationHashId'))
            ->firstOrFail();

        /** @var \App\Models\Payment|null $firstPayment */
        $firstPayment = $donation->payments->last();

        if ($firstPayment === null) {
            $this->error('No payment found. Consider run payment:update-result first!');
            return -1;
        }

        $ecpayData = [
            'MerchantID' => config('services.ecpay.merchant_id'),
            'MerchantTradeNo' => 'ISF' . $firstPayment->hashid,
            'TimeStamp' => time(),
        ];

        $ecpayData['CheckMacValue'] = $this->ecpay->generateCheckSum($ecpayData);

        $response = Http::asForm()->post($this->ecpay->getFullUrl('/Cashier/QueryCreditCardPeriodInfo'), $ecpayData);

        $execLog = collect($response->json('ExecLog', []));

        if ($execLog->isEmpty()) {
            $this->error('ExecLog is empty. Check your input.');
            return -1;
        }

        $donation->payments()->saveMany(
            $execLog
                ->whereNotIn('TradeNo', $donation->payments->pluck('gateway_id'))
                ->map(function ($log) {
                    $payment = new Payment();
                    $payment->timestamps = false;

                    $payment->status = (int)$log['RtnCode'] === 1 ? PaymentStatus::PAID() : PaymentStatus::FAILED();
                    $payment->gateway_id = $log['TradeNo'];
                    $payment->amount = $log['amount'];
                    $payment->created_at = $log['process_date'];
                    $payment->updated_at = $log['process_date'];

                    return $payment;
                })
        );

        $donation->updateLatestPayment();

        return 0;
    }
}
