<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\AdminCategoryResource;
use App\Http\Resources\CategoryResource;
use App\Http\Responses\ErrorResponse;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('parent_id', null)->get();
        $categories = AdminCategoryResource::collection($categories->load('subCategories'));
        return $categories;
    }

    public function showAll()
    {
        $categories = Category::where('parent_id', null)->get();
        return CategoryResource::collection($categories->load('subCategories'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {

        if ($request->has('icon')) {
            $icon = $request->file('icon');
            $iconPath = $icon->store(env('CATEGORY_IMAGE_UPLOAD_PATH'), 'public');
        }
        try {
            $category = Category::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'parent_id' => $request->parent_id,
                'icon' => $iconPath ?? null
            ]);
        } catch (\Throwable $th) {
            return new ErrorResponse($th, 'دسته بندی با موفقیت افزوده نشد');
        }
        return response([
            'message' => 'دسته بندی با موفقیت افزوده شد',
            'data' => $category
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category->load('subCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {

        if ($request->file('icon') != null) {
            $icon = $request->file('icon');
            $iconPath = $icon->store(env('CATEGORY_IMAGE_UPLOAD_PATH'), 'public');
            if ($category->icon)
                Storage::delete($category->icon);
        }


        try {
            $category->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'parent_id' => $request->parent_id,
                'icon' => $iconPath ?? $category->icon
            ]);
        } catch (\Throwable $th) {
            return new ErrorResponse($th, 'دسته بندی با موفقیت ویرایش نشد');
        }
        return response([
            'message' => 'دسته بندی با موفقیت ویرایش شد',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            if ($category->parent) {
                $category->parent->products()->saveMany($category->products);
                if ($category->subCategories->count() > 0) {
                    $category->parent->subCategories()->saveMany($category->subCategories);
                }
            } else {
                $category->products()->delete();
                if ($category->subCategories->count() > 0) {
                    foreach ($category->subCategories as $subCategory) {
                        $subCategory->update([
                            'parent_id' => null
                        ]);
                    }
                }
            }

            $category->delete();
        } catch (\Throwable $th) {
            return new ErrorResponse($th, 'حذف دسته بندی با موفقیت انجام نشد');
        }

        return response([
            'message' => 'دسته بندی با موفقیت حذف شد'
        ]);
    }

    public function getHomepageCategories()
    {
        $categories = Category::parents()->get();
        $categories = CategoryResource::collection($categories->load('subCategories'));
        return $categories;
    }
}
