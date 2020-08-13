<?php

namespace App\Providers;

use Auth0\Login\Contract\Auth0UserRepository as Auth0Contract;
use Auth0\Login\Repository\Auth0UserRepository;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Date;
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
        Paginator::useTailwind();

        Date::use(CarbonImmutable::class);
        Date::macro('when', function (bool $condition, callable $callback) {
            return $condition ? $callback($this) : $this;
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
