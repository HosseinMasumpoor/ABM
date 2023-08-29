<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'slug'=> $this->faker->unique()->slug(),
            'name'=> $this->faker->name(),
            'image'=> $this->faker->imageUrl,
            'price'=> $this->faker->numberBetween(520000, 690000),
            'offPrice'=> $toss ? $this->faker->numberBetween(440000, 515000) : null,
            'color'=> $this->faker->colorName(),
            'colorCode'=> $this->faker->hexColor(),
            'off_date_from' => Carbon::now(),
            'off_date_to' => Carbon::now()->addDays(30)
        ];
    }
}
