<?php

namespace App\Observers;

use App\Models\Image;
use Storage;
use Str;

class ImageObserver
{
    /**
     * Handle the Image "created" event.
     */
    public function created(Image $image): void
    {
        //
    }

    /**
     * Handle the Image "updated" event.
     */
    public function updated(Image $image): void
    {
        //
    }

    /**
     * Handle the Image "deleted" event.
     */
    public function deleting(Image $image): void
    {
        $src = $image->getRawOriginal('src');
        if (!Str::startsWith($src, env('PRODUCT_IMAGE_UPLOAD_PATH') . '/test/'))
            Storage::delete($src);
    }

    /**
     * Handle the Image "restored" event.
     */
    public function restored(Image $image): void
    {
        //
    }

    /**
     * Handle the Image "force deleted" event.
     */
    public function forceDeleted(Image $image): void
    {
        //
    }
}
