<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Responses\ErrorResponse;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('parent_id', null)->get();
        return CategoryResource::collection($categories->load('subCategories'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'slug' => 'required|unique:categories',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable'
        ]);

        try {
            $category = Category::create($request->all());
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
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string',
            'slug' => 'required|unique:categories,slug,'.$category->id,
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable'
        ]);

        try {
            $category->update($request->all());
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
        try{
            $category->delete();
        }
        catch(\Throwable $th){
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
