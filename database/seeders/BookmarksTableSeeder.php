<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookmarksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = User::all();
        $products = Product::all();
        foreach ($users as $user) {
            foreach (range(0, random_int(2, 10)) as $value) {
                Bookmark::factory()->create([
                    'user_id' => $user->id,
                    'product_id' => $products->random()->id
                ]);
            }
        }
    }
}
