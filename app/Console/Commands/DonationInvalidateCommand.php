<?php

namespace App\Console\Commands;

use App\Enums\DonationStatus;
use App\Enums\DonationType;
use App\Models\Donation;
use Illuminate\Console\Command;

class DonationInvalidateCommand extends Command
{
    protected $signature = 'donation:invalidate';
    protected $description = 'Mark old donations as invalidate';

    public function handle()
    {
        $expires = now()->subMonths(3)->subDay();
        Donation
            ::query()
            ->where('status', DonationStatus::ACTIVE())
            ->where('type', DonationType::MONTHLY())
            ->with(['latest_payment'])
            ->each(function (Donation $donation) use ($expires) {
                // if active
                if (optional($donation->getLastPaymentTime())->isAfter($expires)) {
                    return;
                }

                $donation->status = DonationStatus::INACTIVE();
                $donation->save();
            });
        return 0;
    }
}
