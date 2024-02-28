<?php

namespace App\Http\Controllers;

use App\Http\Requests\Address\AddAddressRequest;
use App\Http\Requests\Address\EditAddressRequest;
use App\Http\Responses\ErrorResponse;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddAddressRequest $request)
    {
        $user = auth()->user();

        try {
            $address = $user->addresses()->create($request->all());
        } catch (\Throwable $th) {
            $message = 'افزودن آدرس موفقیت آمیز نبود';
            return new ErrorResponse($th, $message);
        }

        return response([
            'message' => 'آدرس با موفقیت افزوده شد',
            'data' => $address
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditAddressRequest $request, Address $address)
    {
        $user = auth()->user();

        try {
            $address->update($request->all());
        } catch (\Throwable $th) {
            $message = 'ویرایش آدرس موفقیت آمیز نبود';
            return new ErrorResponse($th, $message);
        }

        return response([
            'message' => 'آدرس با موفقیت ویرایش شد',
            'data' => $address
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        //
    }
}
