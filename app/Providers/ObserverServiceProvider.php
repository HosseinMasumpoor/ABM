<?php

namespace App\Providers;

use App\Models\Banner;
use App\Observers\BannerObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Banner::observe(BannerObserver::class);
    }
}
