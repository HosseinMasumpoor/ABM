<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ybazli\Faker\Facades\Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = collect([
            'پیراهن',
            'شلوار',
            'جوراب',
            'ساعت مچی',
            'پالتو',
            'کت',
            'کت و شلوار',
            'دستبند',
            'کلاه',
            'دستکش',
            'کوهنوردی',
            'مجلسی',
            'ساعت هوشمند',
            'ساعت آنالوگ',
            'تاپ',
            'تیشرت',
            'پاشنه بلند',
            'بلوز',
            'کمربند',
            'کیف',
            'گردنبند',
            'انگشتر'
        ]);
        return [
            'slug'=> $this->faker->unique()->slug(),
            'name' => $categories->random(),
        ];
    }
}
