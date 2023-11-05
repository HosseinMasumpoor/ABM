<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;

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
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
    }

    function deleting(Category $category): void
    {
        // Storage::delete('http://localhost:8000/storage/categories/ZyXZcNgbOJi0W4mmmHOSPwNzUKtveac7dejAZ4fx.png');
        // dd($category->icon);
        // if ($category->icon) {
        //     Storage::delete($category->icon);
        // }
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
