<?php

namespace App\Observers;

use App\Models\Banner;
use Storage;

class BannerObserver
{
    /**
     * Handle the Banner "created" event.
     */
    public function created(Banner $banner): void
    {
    }

    /**
     * Handle the Banner "updated" event.
     */
    public function updated(Banner $banner): void
    {
    }

    public function updating(Banner $banner): void
    {
        $src = $banner->getRawOriginal('src');
        Storage::delete($src);
    }

    /**
     * Handle the Banner "deleted" event.
     */
    public function deleted(Banner $banner): void
    {
        //
        $src = $banner->getRawOriginal('src');
        Storage::delete($src);
    }

    /**
     * Handle the Banner "restored" event.
     */
    public function restored(Banner $banner): void
    {
        //
    }

    /**
     * Handle the Banner "force deleted" event.
     */
    public function forceDeleted(Banner $banner): void
    {
        //
    }
}
