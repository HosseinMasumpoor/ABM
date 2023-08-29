<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Address;
use App\Models\Attribute;
use App\Models\Bookmark;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Size;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::truncate();
        Product::truncate();
        Size::truncate();
        Attribute::truncate();
        Image::truncate();
        Brand::truncate();
        User::truncate();
        Order::truncate();
        Address::truncate();
        OrderItem::truncate();
        Slider::truncate();
        Comment::truncate();
        Bookmark::truncate();

        $users = User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Hossein',
            'email' => 'ahoseinmasumpoora@gmail.com',
            'password' => bcrypt('password')
        ]);

        $users->add($user);

        $brands = Brand::factory(10)->create();

        $parentCategories = Category::factory(10)->create();

        $categories = collect();
        for ($i=0; $i < 15; $i++) {
            $category = Category::factory()->create([
                'parent_id' => $parentCategories->random()->id
            ]);
            $categories->add($category);
        }

        $subCategories = collect();
        for ($i=0; $i < 30; $i++) {
            $category = Category::factory()->create([
                'parent_id' => $categories->random()->id
            ]);
            $subCategories->add($category);
        }
        $products = Product::factory(50)->create([
            'category_id' => $subCategories->random()->id,
            'brand_id' => $brands->random()->id
        ]);

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

        foreach ($users as $user) {
            Bookmark::factory(random_int(2, 10))->create([
                'user_id' => $user->id,
                'product_id' => $products->random()->id
            ]);

            Address::factory(2)->create([
                'user_id' => $user->id,
                'cellphone' => '09111111111',
                'postalCode' => '3190000000'
            ]);
        }

        Slider::factory(5)->create([
            'type' => "hompage-main"
        ]);

        Slider::factory(4)->create([
            'type' => "hompage-categories-men"
        ]);

        Slider::factory(4)->create([
            'type' => "hompage-categories-women"
        ]);

        Slider::factory(4)->create([
            'type' => "hompage-categories-children"
        ]);

        $counter = 0;
        $productVariations = Size::all();
        for ($i=0; $i < 20; $i++) {
            $counter++;
            $selectedUser = $users->random();
            $order = Order::factory()->create([
                'user_id' => $selectedUser->id,
                'address_id' => $selectedUser->addresses->random()->id,
                'total_price' => 0
            ]);
            $totalPrice = 0;
            for ($j=0; $j < random_int(1, 3); $j++) {
                $selectedVariation = $productVariations->random();
                $price = $selectedVariation->product->price;
                $totalPrice += $price;
                $quantity = random_int(1, 3);
                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'size_id' => $selectedVariation->id,
                    'price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $price * $quantity,
                ]);
            }
            $order->update([
                'total_price' => $totalPrice
            ]);

        }



        // my seeds
        $brand = Brand::factory()->create([
            'name' => 'هرمس',
            'slug' => 'هرمس',
        ]);

        $myParentCategory = Category::factory()->create([
            'name' => 'زنانه',
            'slug' => 'زنانه',
        ]);
        $myCategory = Category::factory()->create([
            'name' => 'لباس',
            'slug' => 'لباس-زنانه',
            'parent_id' => $myParentCategory->id
        ]);

        $mySubCategory = Category::factory()->create([
            'name' => 'مانتو',
            'slug' => 'مانتو-زنانه',
            'parent_id' => $myCategory->id
        ]);

        $product = Product::factory()->create([
            'slug'=> 'مانتوی-زنانه-هرمس',
            'name'=> 'مانتوی زنانه تابستانه هرمس',
            'image'=> 'https://via.placeholder.com/640x480.png/0044ff?text=minima',
            'price'=> 490000,
            'offPrice'=> 450000,
            'color'=> 'مشکی',
            'colorCode'=> '#000000',
            'brand_id' => $brand->id,
            'category_id' => $mySubCategory->id
        ]);

        Attribute::create([
            'product_id' => $product->id,
            'name' => 'جنس',
            'value' => 'نخی',
        ]);

        Attribute::create([
            'product_id' => $product->id,
            'name' => 'نوع',
            'value' => 'جلوباز',
        ]);

        foreach ($sizes as $size) {
            Size::factory()->create([
                'size' => $size,
                'product_id' => $product->id,
                'stock' => 35
            ]);
        }

        Image::factory(4)->create([
            'product_id' => $product->id
        ]);

        dump('done');
    }
}
