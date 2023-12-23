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
        $files = scandir(public_path("storage/categories/test"));
        $testImages = array_diff($files, [".", ".."]);
        $randomIndex = array_rand($testImages);
        $image =  env('CATEGORY_IMAGE_UPLOAD_PATH', 'categories') . '/test/' . $testImages[$randomIndex];

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
            'slug' => $this->faker->unique()->slug(),
            'name' => $categories->random(),
            'icon' => $image
        ];
    }
}
