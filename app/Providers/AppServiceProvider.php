<?php

namespace App\Providers;

use App\Models\Order;
use App\Services\AdminService;
use App\Services\OrderService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('ahotoService', function () {
            return new AdminService();
        });
        $this->app->bind('ahotoService', function () {
            return new OrderService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
