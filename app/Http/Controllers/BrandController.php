<?php

namespace App\Http\Controllers;

use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Http\Responses\ErrorResponse;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return BrandResource::collection(Brand::paginate($request->items_perpage ?? 10));
    }

    public function showAll()
    {
        $brands = Brand::all();
        return response()->json([
            'data' => $brands
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        try {
            $brand = Brand::create([
                'name' => $request->name,
                'slug' => $request->slug
            ]);
        } catch (\Throwable $th) {
            return new ErrorResponse($th, 'برند به درستی ذخیره نشد', 500);
        }

        return response([
            'message' => 'برند با موفقیت ایجاد شد',
            'data' => $brand,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        try {
            $brand->update([
                'name' => $request->name,
                'slug' => $request->slug
            ]);
        } catch (\Throwable $th) {
            return new ErrorResponse($th, 'برند به درستی ویرایش نشد', 500);
        }

        return response([
            'message' => 'برند با موفقیت ویرایش شد',
            'data' => $brand,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        try {
            $brand->products()->delete();
            $brand->delete();
        } catch (\Throwable $th) {
            $message = 'حذف برند با موفقیت انجام نشد';
            return new ErrorResponse($th, $message, 500);
        }

        return response([
            'message' => 'برند با موفقیت حذف شد'
        ]);
    }
}
