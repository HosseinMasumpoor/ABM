<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::truncate();
        Attribute::truncate();
        Size::truncate();
        Image::truncate();
        Comment::truncate();

        $subCategories = Category::whereNot('parent_id', null)->get();
        $brands = Brand::all();
        $users = User::all();

        $products = collect();
        for ($i = 0; $i < 1000; $i++) {
            $product = Product::factory()->create([
                'category_id' => $subCategories->random()->id,
                'brand_id' => $brands->random()->id
            ]);
            $products->add($product);
        }

        $sizes = ["sm", 'md', 'lg', 'xl', '2xl', '3xl'];

        foreach ($products as  $product) {
            Attribute::factory(5)->create([
                'product_id' => $product->id
            ]);

            foreach ($sizes as $size) {
                Size::factory()->create([
                    'size' => $size,
                    'product_id' => $product->id
                ]);
            }

            Image::factory(4)->create([
                'product_id' => $product->id
            ]);

            Comment::factory()->count(random_int(2, 20))->create([
                'product_id' => $product->id,
                'user_id' => $users->random()->id
            ]);
        }
    }
}
