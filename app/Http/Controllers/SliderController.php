<?php

namespace App\Http\Controllers;

use App\Http\Responses\ErrorResponse;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SliderController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::all();
        return $sliders;


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'link' => "required|url",
            'src' => "required",
        ]);



        try{
            $slider = Slider::create([
                'link' => $request->link,
                'src' => $request->src,
                'type' => 'homepage-main'
            ]);
        }
        catch(\Throwable $ex){
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
    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'link' => "required|url",
            'src' => "required",
        ]);

        try {
            $slider->update([
                'link' => $request->link,
                'src' => $request->src,
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
