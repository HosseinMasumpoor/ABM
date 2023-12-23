<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Banner>
 */
class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $files = scandir(public_path("storage/banners/test"));
        $testImages = array_diff($files, [".", ".."]);
        $randomIndex = array_rand($testImages);
        $image =  env('BANNER_IMAGE_UPLOAD_PATH', 'banners') . '/test/' . $testImages[$randomIndex];

        return [
            'src' => $image,
            'link' => $this->faker->url(),
            'type' => "homepage",
            'order' => 0
        ];
    }
}
