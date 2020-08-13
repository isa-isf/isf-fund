<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_logs', function (Blueprint $table) {
            $table->id();

            $table->string('email');
            $table->enum('result', ['success', 'challenging', 'failed:unknown-user', 'failed:password', 'failed:challenge']);

            $table->ipAddress('ip_address');
            $table->string('user_agent');
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('login_logs');
    }
}
