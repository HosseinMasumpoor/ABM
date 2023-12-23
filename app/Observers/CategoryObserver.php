<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Str;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "updating" event.
     */
    public function updating(Category $category): void
    {
        if (request()->has('icon')) {
            $src = $category->getRawOriginal('icon');
            if ($src && !Str::startsWith($src, env('CATEGORY_IMAGE_UPLOAD_PATH') . '/test/'))
                Storage::delete($src);
        }
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
    }

    function deleting(Category $category): void
    {
        $src = $category->getRawOriginal('icon');
        Storage::delete($src);
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        //
    }
}
