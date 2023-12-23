<?php

namespace Database\Seeders;

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
        $counter = 0;
        $productVariations = Size::all();
        $users = User::all();
        for ($i = 0; $i < 20; $i++) {
            $counter++;
            $selectedUser = $users->random();
            $order = Order::factory()->create([
                'user_id' => $selectedUser->id,
                'address_id' => $selectedUser->addresses->random()->id,
                'total_price' => 0
            ]);
            $code = 1 . str_pad($order->id, 5, '0', STR_PAD_LEFT) . str_pad($selectedUser->id, 3, '0', STR_PAD_LEFT);
            $order->update([
                'code' => $code
            ]);
            $totalPrice = 0;
            for ($j = 0; $j < random_int(1, 3); $j++) {
                $selectedVariation = $productVariations->random();
                $price = $selectedVariation->product->price;
                $totalPrice += $price;
                $quantity = random_int(1, 3);
                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'size_id' => $selectedVariation->id,
                    'product_id' => $selectedVariation->product,
                    'size' => $selectedVariation->size,
                    'price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $price * $quantity,
                ]);
            }
            $order->update([
                'total_price' => $totalPrice
            ]);
        }
    }
}
