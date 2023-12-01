<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {

        $files = scandir(public_path("storage/products/test"));
        $testImages = array_diff($files, [".", ".."]);
        $randomIndex = array_rand($testImages);

        $image =  env('PRODUCT_IMAGE_UPLOAD_PATH') . '/test/' . $testImages[$randomIndex];
        return [
            'src' => $image
        ];
    }
}
