<?php

namespace App\Observers;

use App\Models\Slider;
use Storage;
use Str;

class SliderObserver
{
    /**
     * Handle the Slider "created" event.
     */
    public function created(Slider $slider): void
    {
        //
    }

    /**
     * Handle the Slider "updating" event.
     */
    public function updating(Slider $slider): void
    {
        if (request()->has('src')) {
            $src = $slider->getRawOriginal('src');
            if (!Str::startsWith($src, env('SLIDER_IMAGE_UPLOAD_PATH') . '/test/'))
                Storage::delete($src);
        }
    }

    /**
     * Handle the Slider "updated" event.
     */
    public function updated(Slider $slider): void
    {
        //
    }

    /**
     * Handle the Slider "deleted" event.
     */
    public function deleted(Slider $slider): void
    {
        $src = $slider->getRawOriginal('src');
        if (!Str::startsWith($src, env('SLIDER_IMAGE_UPLOAD_PATH') . '/test/'))
            Storage::delete();
    }

    /**
     * Handle the Slider "restored" event.
     */
    public function restored(Slider $slider): void
    {
        //
    }

    /**
     * Handle the Slider "force deleted" event.
     */
    public function forceDeleted(Slider $slider): void
    {
        //
    }
}
