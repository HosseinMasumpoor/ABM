<?php

namespace App\Http\Controllers;

use App\Http\Requests\Slider\StoreSliderRequest;
use App\Http\Requests\Slider\UpdateSliderRequest;
use App\Http\Resources\SliderResource;
use App\Http\Responses\ErrorResponse;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sliders = Slider::paginate($request->items_perpage ?? 10);
        $sliders = SliderResource::collection($sliders);
        return $sliders;
    }

    public function showAll()
    {
        $sliders = Slider::latest()->limit(10)->get();
        return response()->json([
            'data' => $sliders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSliderRequest $request)
    {

        $slidersCount = Slider::all()->count();
        if ($slidersCount >= 10) {
            return response()->json([
                'message' => 'تعداد اسلایدرها بیشتر از 10 است. برای افزودن اسلایدر جدید باید تعدادی را حذف نمایید'
            ], 500);
        }

        $image = $request->file('src');
        $src = $image->store(env('SLIDER_IMAGE_UPLOAD_PATH'), 'public');
        try {
            $slider = Slider::create([
                'link' => $request->link,
                'src' => $src,
                'type' => 'homepage-main'
            ]);
        } catch (\Throwable $ex) {
            $message = 'اسلایدر با موفقیت ایجاد نشد، دوباره تلاش کنید';
            return new ErrorResponse($ex, $message, 500);
        }

        return Response::json($slider)->setData([
            'message' => 'اسلایدر با موفقیت ساخته شد',
            'data' => $slider
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        return $slider;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSliderRequest $request, Slider $slider)
    {

        if ($request->has('src')) {
            $image = $request->file('src');
            $src = $image->store(env('SLIDER_IMAGE_UPLOAD_PATH'), 'public');
            Storage::delete($slider->src);
        }

        try {
            $slider->update([
                'link' => $request->link,
                'src' => $src ?? $slider->src,
            ]);
        } catch (\Throwable $th) {
            $message = 'متأسفانه ویرایش اسلایدر با موفقیت انجام نشد';
            return new ErrorResponse($th, $message, 500);
        }
        return Response::json($slider)->setData([
            'message' => 'اسلایدر با موفقیت ویرایش شد',
            'data' => $slider
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        try {
            $slider->delete();
        } catch (\Throwable $th) {
            $message = 'حذف اسلایدر با موفقیت انجام نشد';
            return new ErrorResponse($th, $message, 500);
        }

        return response([
            'message' => 'اسلایدر با موفقیت حذف شد'
        ]);
    }
}
