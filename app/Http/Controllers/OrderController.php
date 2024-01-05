<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\OrderRequest;
use App\Http\Responses\ErrorResponse;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Size;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
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
    public function store(OrderRequest $request)
    {
        $result =  $this->checkCart($request->items);
        // if (count($result["errors"]) > 0) {
        //     return $result;
        // }

        // Deletes Error Messages From Cart Items Array
        unset($result["errors"]);

        $totalPrice = $this->calcTotalPrice($result);
        $userId = auth()->id();

        try {
            DB::beginTransaction();
            $order = Order::create([
                'user_id' => $userId,
                'address_id' => $request->address_id,
                'total_price' => $totalPrice,
                'code' => generageOrderCode($userId),
                'description' => $request->description,
                'status' => 0,
                'payment_status' => 0
            ]);

            foreach ($result as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem["id"],
                    'size_id' => $cartItem["sizes"]["id"],
                    'size' => $cartItem["sizes"]["size"],
                    'price' => $cartItem["offPrice"],
                    'quantity' => $cartItem["quantity"],
                    'subtotal' => $cartItem["quantity"] * $cartItem["offPrice"],
                ]);

                // Update Stock
                $productSize = Size::find($cartItem["sizes"]["id"]);
                $productSize->update([
                    'stock' => $productSize->stock - $cartItem["quantity"]
                ]);
            }

            DB::commit();

            return response([
                'message' => 'سفارش شما با موفقیت ثبت گردید'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return new ErrorResponse($th, "ثبت سفارش با موفقیت انجام نشد", 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    private function checkCart($cartItems)
    {
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
                $cartItems[$index]["price"] = $size->product->price;
                $cartItems[$index]["offPrice"] = $size->product->offPrice;
                $cartItems[$index]["offPercent"] = $size->product->offPercent;
                $cartItems[$index]["off_date_from"] = $size->product->off_date_from;
                $cartItems[$index]["off_date_to"] = $size->product->off_date_to;
                $cartItems[$index]["color"] = $size->product->color;
                $cartItems[$index]["colorCode"] = $size->product->colorCode;
                $cartItems[$index]["image"] = $size->product->image;
                $cartItems[$index]["slug"] = $size->product->slug;
                $cartItems[$index]["sizes"] = [
                    'id' => $size->id,
                    'size' => $size->size,
                    'stock' => $size->stock
                ];


                // چک کردن تغییر نکردن قیمت
                if ($size->product->offPrice != $item["offPrice"]) {


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
                    'text' => "متاسفانه محصول با این سایز انتخاب شده دیگر موجود نیست",
                    'type' => "deleted"
                ];
                $cartItems[$index]["sizes"] = null;

                array_push($error, $message);
            }
            $cartItems[$index]["messages"] = $error;
            $errors->push($error);
        }
        $cartItems["errors"] = $errors->toArray();

        return $cartItems;
    }

    private function calcTotalPrice($cartItems)
    {
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item["offPrice"] * $item["quantity"];
        }
        return $totalPrice;
    }

    private function updateStock()
    {
    }
}
