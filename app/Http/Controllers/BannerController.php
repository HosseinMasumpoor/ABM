<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerStoreRequest;
use App\Http\Requests\Banner\BannerUpdateRequest;
use App\Http\Resources\BannerResource;
use App\Http\Responses\ErrorResponse;
use App\Models\Banner;
use Exception;
use Illuminate\Http\Request;
use Throwable;

class BannerController extends Controller
{

    public function showBanners(Request $request)
    {
        $banners = $request->has('type') ? Banner::where('type', $request->type)->get() : Banner::all();
        $banners = BannerResource::collection($banners);
        return $banners;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $banners = Banner::paginate($request->items_perpage ?? 10);
        $banners = BannerResource::collection($banners);
        return $banners;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BannerStoreRequest $request)
    {
        $image = $request->file('image');
        $folder = config('filesystems.banner_image_upload_path');

        $src = $image->store($folder, 'public');

        try {
            $banner = Banner::create([
                'link' => $request->link,
                'src' => $src,
                'type' => $request->type,
                'order' => $request->order ?? 0
            ]);
        } catch (Throwable $th) {
            $message = 'بنر با موفقیت ایجاد نشد، دوباره تلاش کنید';
            return new ErrorResponse($th, $message, 500);
        }

        return response([
            'message' => 'بنر با موفقیت ساخته شد',
            'data' => $banner
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        return $banner;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BannerUpdateRequest $request, Banner $banner)
    {
        // if ($request->has('src')) {
        //     $image = $request->file('src');
        //     $src = $image->store(env('SLIDER_IMAGE_UPLOAD_PATH'), 'public');
        //     Storage::delete($slider->src);
        // }

        // dd($request->all());
        if ($request->has('image')) {
            $image = $request->file('image');
            $folder = config('filesystems.banner_image_upload_path');
            $src = $image->store($folder, 'public');
        }

        try {
            $banner->update([
                'link' => $request->link,
                'src' => $src ?? $banner->src,
                'type' => $request->type,
                'order' => $request->order ?? 0
            ]);
        } catch (\Throwable $th) {
            $message = 'متأسفانه ویرایش بنر با موفقیت انجام نشد';
            return new ErrorResponse($th, $message, 500);
        }
        return response([
            'message' => 'بنر با موفقیت ویرایش شد',
            'data' => $banner
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        try {
            $banner->delete();
        } catch (\Throwable $th) {
            $message = 'حذف بنر با موفقیت انجام نشد';
            return new ErrorResponse($th, $message, 500);
        }

        return response([
            'message' => 'بنر با موفقیت حذف شد'
        ]);
    }
}
