<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateDonationStatuses extends Migration
{
    public function up()
    {
        DB::table('donations')->update(['status' => 'inactive']);

        // Mark donations where there are payments in recent 3 months as active
        $statement = DB
            ::getPdo()
            ->prepare("update donations set donations.status = 'active' where donations.id in (select payments.donation_id from payments where payments.created_at >= ?)");
        $statement->execute([now()->subMonths(3)->toDateTimeString()]);
    }

    public function down()
    {
        //
    }
}
