<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $brandsName = collect([
            'آرمانی',
            'فندی',
            'ورساچه',
            'بربری',
            'رالف لارن',
            'شانل',
            'پرادا',
            'هرمس',
            'گوچی',
            'لویی ویتون',
            'هرمس',
        ]);

        foreach ($brandsName as $brandName) {
            $brand = Brand::factory()->create([
                'name' => $brandName
            ]);
        }
    }
}
