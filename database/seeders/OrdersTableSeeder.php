<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Size;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Order::truncate();
        OrderItem::truncate();
        $counter = 0;
        $productVariations = Size::all();
        $users = User::all();
        for ($i = 0; $i < 20; $i++) {
            $counter++;
            $selectedUser = $users->random();
            if ($selectedUser->addresses()->count() == 0) {
                Address::factory()->for($selectedUser)->create();
            }
            // while ($selectedUser->addresses()->count() == 0) {
            //     $selectedUser = $users->random();
            // }
            $order = Order::factory()->create([
                'user_id' => $selectedUser->id,
                'address_id' => $selectedUser->addresses->random()->id,
                'total_price' => 0,
                'code' => generageOrderCode($selectedUser->id)
            ]);

            $totalPrice = 0;
            for ($j = 0; $j < random_int(1, 3); $j++) {
                $selectedVariation = $productVariations->random();
                $product = $selectedVariation->product;
                $offPrice = $product->offPrice;
                $quantity = random_int(1, 3);
                $subtotal = $offPrice * $quantity;
                $totalPrice += $subtotal;
                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'size_id' => $selectedVariation->id,
                    'product_id' => $selectedVariation->product,
                    'size' => $selectedVariation->size,
                    'price' => $offPrice,
                    'quantity' => $quantity,
                    'subtotal' => $offPrice * $quantity,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'product_offPrice' => $product->offPrice,
                    'product_color' => $product->color,
                    'product_colorCode' => $product->colorCode,
                ]);
            }
            $order->update([
                'total_price' => $totalPrice
            ]);
        }
    }
}
