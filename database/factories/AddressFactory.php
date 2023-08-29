<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = collect(['خانه', 'خانه 2']);
        return [
            'province' => 'تهران',
            'city' => 'تهران',
            'address' => 'میدان هفت تیر خیابان فاطمی کوچه نرگس2 پلاک 14 واحد 2',
            'latitude' => $this->faker->numberBetween(200, 260),
            'longitude' => $this->faker->numberBetween(200, 260),
            'title' => $titles->random()
        ];
    }
}
