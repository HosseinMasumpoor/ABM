<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SlidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Slider::truncate();
        //
        Slider::factory(10)->create([
            'type' => "hompage-main"
        ]);

        // Slider::factory(4)->create([
        //     'type' => "hompage-categories-men"
        // ]);

        // Slider::factory(4)->create([
        //     'type' => "hompage-categories-women"
        // ]);

        // Slider::factory(4)->create([
        //     'type' => "hompage-categories-children"
        // ]);
    }
}
