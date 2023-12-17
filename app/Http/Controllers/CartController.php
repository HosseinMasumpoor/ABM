<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartCheckRequest;
use App\Models\Size;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //
    public function checkCart(CartCheckRequest $request)
    {
        $cartItems = $request->all();
        $errors = collect();
        foreach ($cartItems as $index => $item) {
            $size = Size::find($item["sizes"]["id"]);
            $error = [];
            // چک کردن وجود سایز
            if ($size) {
                // چک کردن تغییر نکردن قیمت
                if ($size->product->offPrice != $item["offPrice"]) {
                    $cartItems[$index]["price"] = $size->product->price;
                    $cartItems[$index]["offPrice"] = $size->product->offPrice;
                    $cartItems[$index]["offPercent"] = $size->product->offPercent;
                    $cartItems[$index]["off_date_from"] = $size->product->off_date_from;
                    $cartItems[$index]["off_date_to"] = $size->product->off_date_to;
                    $message = [
                        'text' => "قیمت محصول در فروشگاه تغییر کرده است",
                        'type' => "price"
                    ];
                    array_push($error, $message);
                }

                // چک کردن وجود موجودی
                if ($size->stock < $item["quantity"]) {
                    if ($size->stock == 0) {
                        $message = [
                            'text' => "متأسفانه موجودی محصول به اتمام رسید است",
                            'type' => "deleted"
                        ];
                    } else {
                        $cartItems[$index]["quantity"] = $size->stock;
                        $message = [
                            'text' => "موجودی محصول کمتر از تعداد درخواستی شماست",
                            'type' => "quantity"
                        ];
                    }

                    array_push($error, $message);
                }
            } else {
                $message = [
                    'text' => "سایز انتخاب شده از محصول از فروشگاه حذف شده است. دوباره به سبد خرید اضافه کنید",
                    'type' => "deleted"
                ];

                array_push($error, $message);
            }
            $cartItems[$index]["messages"] = $error;
            $errors->push($error);
        }
        // dd($cartItems);
        return response()->json($cartItems);
        // dd($errors);
        // dd($request->all());
    }
}
