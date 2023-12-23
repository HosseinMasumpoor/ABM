<?php

namespace App\Providers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Slider;
use App\Observers\BannerObserver;
use App\Observers\CategoryObserver;
use App\Observers\ImageObserver;
use App\Observers\ProductObserver;
use App\Observers\SliderObserver;
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
        Slider::observe(SliderObserver::class);
        Category::observe(CategoryObserver::class);
        Product::observe(ProductObserver::class);
        Image::observe(ImageObserver::class);
    }
}
