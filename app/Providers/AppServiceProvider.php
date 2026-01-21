<?php

namespace App\Providers;

use App\Models\StokMasuk;
use App\Observers\StokMasukObserver;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        StokMasuk::observe(StokMasukObserver::class);
    }
}
