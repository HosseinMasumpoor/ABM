<?php

namespace App\Providers;

use App;
use App\Models\Category;
use App\Observers\CategoryObserver;
use Cache;
use Illuminate\Cache\FileStore;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        // Adding get expires in seconds for cache key macro to Cache class
        Cache::macro('getTTL', function (string $key): ?int {
            $fs = new class extends FileStore
            {
                public function __construct()
                {
                    parent::__construct(App::get('files'), config('cache.stores.file.path'));
                }

                public function getTTL(string $key): ?int
                {
                    return $this->getPayload($key)['time'] ?? null;
                }
            };

            return $fs->getTTL($key);
        });
    }
}
