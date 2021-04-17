<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Services\Ecpay;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;

final class PaymentUpdateResultCommand extends Command
{
    protected $signature = 'payment:update-result {paymentHashId?}';
    protected $description = 'Fetch result from ecpay.';

    public function __construct(
        private Ecpay $ecpay
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $paymentHashId = $this->argument('paymentHashId');

        Payment
            ::query()
            ->where('status', PaymentStatus::CREATED())
            ->when(
                $paymentHashId,
                fn ($q) => $q->where('id', Hashid::driver('payment')->decode($paymentHashId)),
                fn ($q) => $q->where('created_at', '>=', now()->subDay()),
            )
            ->with('donation')
            ->get()
            ->each(fn (Payment $p) => $this->processPayment($p));

        return 0;
    }

    private function processPayment(Payment $payment): void
    {
        $ecpayData = [
            'MerchantID' => config('services.ecpay.merchant_id'),
            'MerchantTradeNo' => 'ISF' . $payment->hashid,
            'TimeStamp' => time(),
        ];

        $ecpayData['CheckMacValue'] = $this->ecpay->generateCheckSum($ecpayData);

        $response = Http::asForm()->post($this->ecpay->getFullUrl('/Cashier/QueryTradeInfo/V5'), $ecpayData);

        $responseData = [];
        parse_str($response->body(), $responseData);
        $responseData = optional($responseData);

        if ($responseData['TradeStatus'] === '1') {
            $payment->status = PaymentStatus::PAID();
            $payment->save();
        }
    }
}
