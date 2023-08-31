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
        return [
            'slug'=> $this->faker->unique()->slug(),
            'name'=> Faker::word(),
            'image'=> $this->faker->imageUrl,
            'price'=> round($this->faker->numberBetween(520000, 690000), -3),
            'offPrice'=> $toss ? round($this->faker->numberBetween(440000, 515000), -3) : null,
            'color'=> $colors->random(),
            'colorCode'=> $this->faker->hexColor(),
            'off_date_from' => Carbon::now(),
            'off_date_to' => Carbon::now()->addDays(30)
        ];
    }
}
