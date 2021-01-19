<?php

namespace App\Providers;

use App\Channels\AwsSnsChannel;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Date::use(CarbonImmutable::class);
        Date::macro('when', function (bool $condition, callable $callback) {
            return $condition ? $callback($this) : $this;
        });

        Notification::extend('sms', function () {
            $client = new \Aws\Sns\SnsClient([
                'version' => '2010-03-31',
                'credential' => new \Aws\Credentials\Credentials(
                    config('services.sns.key'),
                    config('services.sns.secret'),
                ),
                'region' => config('services.sns.region', 'us-east-1'),
            ]);
            return new AwsSnsChannel($client);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Auth0Contract::class, Auth0UserRepository::class);
    }
}
