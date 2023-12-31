<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Slider>
 */
class SliderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $files = scandir(public_path("storage/sliders/test"));
        $testImages = array_diff($files, [".", ".."]);
        $randomIndex = array_rand($testImages);
        $image =  env('SLIDER_IMAGE_UPLOAD_PATH', 'sliders') . '/test/' . $testImages[$randomIndex];

        return [
            'src' => $image,
            'link' => $this->faker->url()
        ];
    }
}
