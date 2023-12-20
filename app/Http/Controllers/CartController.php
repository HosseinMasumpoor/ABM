<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartCheckRequest;
use App\Models\Product;
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
            if (empty($size)) {
                $product = Product::find($item['id']);
                $size = $product->sizes()->where('size', $item['sizes']['size'])->first();
            }
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

                    if ($size->product->offPrice > $item["offPrice"]) {
                        if ($item["price"] != $item["offPrice"] && $size->product->price ==  $size->product->offPrice) {
                            $message = [
                                'text' => "متاسفانه زمان تخفیف این محصول به اتمام رسیده‌",
                                'type' => "discount_increase_price"
                            ];
                        } else {

                            $message = [
                                'text' => "قیمت این محصول افزایش پیدا کرده است‌",
                                'type' => "increase_price"
                            ];
                        }
                    } else {
                        if ($item["price"] == $item["offPrice"] && $size->product->price !=  $size->product->offPrice) {
                            $message = [
                                'text' => "این محصول تخفیف خورده است",
                                'type' => "discount_decrease_price"
                            ];
                        } else {

                            $message = [
                                'text' => "قیمت این محصول کاهش پیدا کرده است‌",
                                'type' => "decrease_price"
                            ];
                        }
                    }

                    array_push($error, $message);
                }

                // چک کردن وجود موجودی
                if ($size->stock < $item["quantity"]) {
                    if ($size->stock == 0) {
                        $message = [
                            'text' => "متاسفانه محصول با این سایز انتخاب شده دیگر موجود نیست",
                            'type' => "deleted"
                        ];
                        $cartItems[$index]["sizes"] = null;
                    } else {
                        $cartItems[$index]["quantity"] = $size->stock;
                        $message = [
                            'text' => "موجودی این محصول از تعداد انتخاب شده شما کمتر شده است",
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
                $cartItems[$index]["sizes"] = null;

                array_push($error, $message);
            }
            $cartItems[$index]["messages"] = $error;
            $errors->push($error);
        }

        return response()->json($cartItems);
    }
}
