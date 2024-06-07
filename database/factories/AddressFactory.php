<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ybazli\Faker\Facades\Faker;

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
        $titles = collect(['خانه', 'خانه 2', 'محل کار']);
        return [
            // 'province' => $provinces->random(),
            'province' => Faker::state(),
            'city' => Faker::city(),
            'address' => Faker::address(),
            'latitude' => $this->faker->numberBetween(200, 260),
            'longitude' => $this->faker->numberBetween(200, 260),
            'title' => $titles->random(),
            'name' => Faker::fullName(),
            'cellphone' => Faker::mobile(),
            'postalCode' => '3190000000',
            'number' => $this->faker->numberBetween(1, 100)
        ];
    }
}
