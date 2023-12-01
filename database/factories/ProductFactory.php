<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ybazli\Faker\Facades\Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $toss = random_int(0, 1);
        $colors = collect([
            'آبی',
            'قرمز',
            'سفید',
            'مشکی',
            'طوسی',
            'قهوه ای',
            'بنفش',
            'سبز',
            'زرد',
            'کرم',
            'نارنجی',
            'صورتی'
        ]);

        $files = scandir(public_path("storage/products/test"));
        $testImages = array_diff($files, [".", ".."]);
        $randomIndex = array_rand($testImages);


        $image =  env('PRODUCT_IMAGE_UPLOAD_PATH') . '/test/' . $testImages[$randomIndex];
        return [
            'slug' => $this->faker->unique()->slug(),
            'name' => Faker::word(),
            'image' => $image,
            'price' => round($this->faker->numberBetween(520000, 690000), -3),
            'offPrice' => $toss ? round($this->faker->numberBetween(440000, 515000), -3) : null,
            'color' => $colors->random(),
            'colorCode' => $this->faker->hexColor(),
            'off_date_from' => $toss ? Carbon::now() : null,
            'off_date_to' => $toss ? Carbon::now()->addDays(14) : null
        ];
    }
}
