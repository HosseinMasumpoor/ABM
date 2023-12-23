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

        $this->call(UsersTableSeeder::class);
        $this->call(BrandsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(BookmarksTableSeeder::class);
        $this->call(AddressesTableSeeder::class);
        $this->call(SlidersTableSeeder::class);
        $this->call(BannersTableSeeder::class);
        $this->call(OrdersTableSeeder::class);

        dump('done');
    }
}
