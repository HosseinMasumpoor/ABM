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

        $brandsName = collect([
            'آرمانی',
            'فندی',
            'ورساچه',
            'بربری',
            'رالف لارن',
            'شانل',
            'پرادا',
            'هرمس',
            'گوچی',
            'لویی ویتون',
            'هرمس',
        ]);

        $brands = collect();
        foreach ($brandsName as $brandName) {
            $brand = Brand::factory()->create([
                'name' => $brandName
            ]);
            $brands->add($brand);
        }


        $parentCategories = collect();
        $parentCategoriesName = collect(['مردانه', 'زنانه', 'بچگانه']);
        $categoriesName = collect(['لباس', 'کفش', 'اکسسوری', 'ورزشی']);
        foreach ($parentCategoriesName as $categoryName) {
            $category = Category::factory()->create([
                'name' => $categoryName,
                // 'slug' => str($categoriesName)->slug()
            ]);

            $parentCategories->add($category);
        }

        $categories = collect();

        foreach ($parentCategories as $parentCategory) {
            foreach ($categoriesName as $categoryName) {
                $category = Category::factory()->create([
                    'name' => $categoryName,
                    // 'slug' => str($categoriesName)->slug(),
                    'parent_id' => $parentCategory->id
                ]);
                $categories->add($category);
            }
        }


        $subCategories = collect();
        for ($i = 0; $i < 30; $i++) {
            $category = Category::factory()->create([
                'parent_id' => $categories->random()->id
            ]);
            $subCategories->add($category);
        }

        $products = collect();
        for ($i = 0; $i < 150; $i++) {
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

        foreach ($users as $user) {
            foreach (range(0, random_int(2, 10)) as $value) {
                Bookmark::factory()->create([
                    'user_id' => $user->id,
                    'product_id' => $products->random()->id
                ]);
            }

            Address::factory(2)->create([
                'user_id' => $user->id,
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



        // my seeds
        $brand = Brand::where('name', 'هرمس')->first();

        $myParentCategory = Category::where('name', 'زنانه')->first();
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
            'slug' => 'مانتوی-زنانه-هرمس',
            'name' => 'مانتوی زنانه تابستانه هرمس',
            'image' => 'https://via.placeholder.com/640x480.png/0044ff?text=minima',
            'price' => 490000,
            'offPrice' => 450000,
            'color' => 'مشکی',
            'colorCode' => '#000000',
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
