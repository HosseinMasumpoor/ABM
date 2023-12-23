<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $values = [
            [
                'name' => 'مردانه',
                'slug' => 'mens',
                'parent_id' => null,
            ],
            [
                'name' => 'زنانه',
                'slug' => 'womens',
                'parent_id' => null,
            ],
            [
                'name' => 'بچگانه',
                'slug' => 'kids',
                'parent_id' => null,
            ],
            [
                'name' => 'کفش زنانه',
                'slug' => 'womens-shoes',
                'parent_id' => 2,
            ],
            [
                'name' => 'لباس زنانه',
                'slug' => 'womens-clothing',
                'parent_id' => 2,
            ],
            [
                'name' => 'اکسسوری',
                'slug' => 'womens-accessories',
                'parent_id' => 2,
            ],
            [
                'name' => 'ورزشی',
                'slug' => 'womens-sports',
                'parent_id' => 2,
            ],
            [
                'name' => 'لباس',
                'slug' => 'mens-clothing',
                'parent_id' => 1,
            ],
            [
                'name' => 'کفش',
                'slug' => 'mens-shoes',
                'parent_id' => 1,
            ],
            [
                'name' => 'اکسسوری',
                'slug' => 'mens-accessories',
                'parent_id' => 1,
            ],
            [
                'name' => 'ورزشی',
                'slug' => 'mens-sports',
                'parent_id' => 1,
            ],
            [
                'name' => 'لباس',
                'slug' => 'kids-clothing',
                'parent_id' => 3,
            ],
            [
                'name' => 'کفش',
                'slug' => 'kids-shoes',
                'parent_id' => 3,
            ],
            [
                'name' => 'اکسسوری',
                'slug' => 'kids-accessories',
                'parent_id' => 3,
            ],
            [
                'name' => 'ورزشی',
                'slug' => 'kids-sports',
                'parent_id' => 3,
            ],

            // mens clothing
            [
                'name' => 'پیراهن',
                'slug' => 'mens-clothing-shirt',
                'parent_id' => 8,
            ],
            [
                'name' => 'تی شرت',
                'slug' => 'mens-clothing-t-shirt',
                'parent_id' => 8,
            ],
            [
                'name' => 'شلوار جین',
                'slug' => 'mens-clothing-jeans',
                'parent_id' => 8,
            ],
            [
                'name' => 'لباس راحتی',
                'slug' => 'mens-clothing-home-clothing',
                'parent_id' => 8,
            ],
            [
                'name' => 'جوراب',
                'slug' => 'mens-clothing-socks',
                'parent_id' => 8,
            ],
            [
                'name' => 'لباس زیر',
                'slug' => 'mens-clothing-under-wear',
                'parent_id' => 8,
            ],
            [
                'name' => 'هودی',
                'slug' => 'mens-clothing-hoodi',
                'parent_id' => 8,
            ],
            [
                'name' => 'کاپشن، بارانی و پالتو',
                'slug' => 'mens-clothing-coat',
                'parent_id' => 8,
            ],
            [
                'name' => 'ژاکت و پلیور',
                'slug' => 'mens-clothing-jaket',
                'parent_id' => 8,
            ],
            [
                'name' => 'کت شلوار و لباس رسمی',
                'slug' => 'mens-clothing-suit',
                'parent_id' => 8,
            ],
            // mens shoes
            [
                'name' => 'نیم بوت',
                'slug' => 'mens-shoes-ankle-boots',
                'parent_id' => 4,
            ],
            [
                'name' => 'بوت',
                'slug' => 'mens-shoes-boots',
                'parent_id' => 4,
            ],
            [
                'name' => 'کفش رسمی',
                'slug' => 'mens-shoes-formal-shoes',
                'parent_id' => 4,
            ],
            [
                'name' => 'صندل',
                'slug' => 'mens-shoes-sandals',
                'parent_id' => 4,
            ],
            [
                'name' => 'کفش روزمره',
                'slug' => 'mens-shoes-everyday-shoes',
                'parent_id' => 4,
            ],
            [
                'name' => 'کفش کالج',
                'slug' => 'mens-shoes-driving-shoes',
                'parent_id' => 4,
            ],
            // mens accessories
            [
                'name' => 'کول پشتی',
                'slug' => 'mens-accessories-backpack',
                'parent_id' => 10,
            ],
            [
                'name' => 'کمربند و ساسبند',
                'slug' => 'mens-accessories-belts',
                'parent_id' => 10,
            ],
            [
                'name' => 'عینک',
                'slug' => 'mens-accessories-glasses',
                'parent_id' => 10,
            ],
            [
                'name' => 'شال',
                'slug' => 'mens-accessories-shawl',
                'parent_id' => 10,
            ],
            [
                'name' => 'دستکش',
                'slug' => 'mens-accessories-gloves',
                'parent_id' => 10,
            ],
            [
                'name' => 'کلاه',
                'slug' => 'mens-accessories-headwear',
                'parent_id' => 10,
            ],
            [
                'name' => 'زیور آلات',
                'slug' => 'mens-accessories-jewelry',
                'parent_id' => 10,
            ],
            [
                'name' => 'کراوات و پاپیون',
                'slug' => 'mens-accessories-ties',
                'parent_id' => 10,
            ],
            [
                'name' => 'ساعت مچی',
                'slug' => 'mens-accessories-watches',
                'parent_id' => 10,
            ],
            [
                'name' => 'کیف پول',
                'slug' => 'mens-accessories-bags',
                'parent_id' => 10,
            ],
            // mens sports
            [
                'name' => 'کفش ورزشی',
                'slug' => 'mens-sports-sport-shoes',
                'parent_id' => 10,
            ],
            [
                'name' => 'لباس ورزشی',
                'slug' => 'mens-sports-jogging-suit',
                'parent_id' => 10,
            ],
            //------------//

            //womens clothing
            [
                'name' => 'پوشش اسلامی',
                'slug' => 'womens-clothing-islamicwear',
                'parent_id' => 5,
            ],
            [
                'name' => 'نیم تنه و تاپ',
                'slug' => 'womens-clothing-tops',
                'parent_id' => 5,
            ],
            [
                'name' => 'تی شرت',
                'slug' => 'womens-clothing-t-shirt',
                'parent_id' => 5,
            ],
            [
                'name' => 'شلوار جین',
                'slug' => 'womens-clothing-jeans',
                'parent_id' => 5,
            ],
            [
                'name' => 'شلوارک',
                'slug' => 'womens-clothing-shorts',
                'parent_id' => 5,
            ],
            [
                'name' => 'دامن',
                'slug' => 'womens-clothing-skirt',
                'parent_id' => 5,
            ],
            [
                'name' => 'لباس خواب و راحتی',
                'slug' => 'womens-clothing-homewear',
                'parent_id' => 5,
            ],
            [
                'name' => 'جوراب',
                'slug' => 'womens-clothing-socks',
                'parent_id' => 5,
            ],
            [
                'name' => 'لباس زیر',
                'slug' => 'womens-clothing-under-wear',
                'parent_id' => 5,
            ],
            [
                'name' => 'هودی',
                'slug' => 'womens-clothing-hoodi',
                'parent_id' => 5,
            ],
            [
                'name' => 'کاپشن، بارانی و پالتو',
                'slug' => 'womens-clothing-coat',
                'parent_id' => 5,
            ],
            [
                'name' => 'ژاکت و پلیور',
                'slug' => 'womens-clothing-jaket',
                'parent_id' => 5,
            ],
            [
                'name' => 'لباس مجلسی',
                'slug' => 'womens-clothing-formal-dress',
                'parent_id' => 5,
            ],
            // womens shoes
            [
                'name' => 'کفش کالج',
                'slug' => 'womens-shoes-driving-shoes',
                'parent_id' => 4,
            ],
            [
                'name' => 'نیم بوت',
                'slug' => 'womens-shoes-ankle-boots',
                'parent_id' => 4,
            ],
            [
                'name' => 'کفش روزمره',
                'slug' => 'womens-shoes-everyday-shoes',
                'parent_id' => 4,
            ],
            [
                'name' => 'بوت ساق بلند',
                'slug' => 'womens-shoes-high-boots',
                'parent_id' => 4,
            ],
            [
                'name' => 'صندل',
                'slug' => 'womens-shoes-sandals',
                'parent_id' => 4,
            ],
            [
                'name' => 'کفش پاشنه دار',
                'slug' => 'womens-shoes-heels-shoes',
                'parent_id' => 4,
            ],
            [
                'name' => 'دمپایی لاانگشتی',
                'slug' => 'womens-shoes-flip-flops',
                'parent_id' => 4,
            ],
            [
                'name' => 'بوت',
                'slug' => 'womens-shoes-boots',
                'parent_id' => 4,
            ],
            [
                'name' => 'دمپایی',
                'slug' => 'womens-shoes-slippers',
                'parent_id' => 4,
            ],
            [
                'name' => 'کفش تخت',
                'slug' => 'womens-shoes-flat-shoes',
                'parent_id' => 4,
            ],



            // womens accessories
            [
                'name' => 'شال و روسری',
                'slug' => 'womens-accessories-Shawls-and-scarves',
                'parent_id' => 6,
            ],
            [
                'name' => 'ساعت مچی',
                'slug' => 'womens-accessories-watches',
                'parent_id' => 6,
            ],
            [
                'name' => 'دستکش',
                'slug' => 'womens-accessories-gloves',
                'parent_id' => 6,
            ],
            [
                'name' => 'کیف',
                'slug' => 'womens-accessories-bags',
                'parent_id' => 6,
            ],
            [
                'name' => 'زیورآلات نقره',
                'slug' => 'womens-accessories-silver-jewelry',
                'parent_id' => 6,
            ],
            [
                'name' => 'کلاه',
                'slug' => 'womens-accessories-headwear',
                'parent_id' => 6,
            ],
            [
                'name' => 'کمربند و ساسبند',
                'slug' => 'womens-accessories-belts',
                'parent_id' => 6,
            ],
            [
                'name' => 'زیورآلات طلا',
                'slug' => 'womens-accessories-gold-jewelry',
                'parent_id' => 6,
            ],
            [
                'name' => 'عینک',
                'slug' => 'womens-accessories-glasses',
                'parent_id' => 6,
            ],
            [
                'name' => 'کوله پشتی',
                'slug' => 'womens-accessories-backpack',
                'parent_id' => 6,
            ],
            // womens sports
            [
                'name' => 'کفش ورزشی',
                'slug' => 'womens-sports-sport-shoes',
                'parent_id' => 7,
            ],
            [
                'name' => 'لباس ورزشی',
                'slug' => 'womens-sports-jogging-suit',
                'parent_id' => 7,
            ],

            //-----------//
            //kids clothing
            [
                'name' => 'لباس پسرانه',
                'slug' => 'kids-clothing-boy-clothing',
                'parent_id' => 12,
            ],
            [
                'name' => 'لباس دخترانه',
                'slug' => 'kids-clothing-girl-clothing',
                'parent_id' => 12,
            ],
            [
                'name' => 'لباس بچگانه',
                'slug' => 'kids-clothing-child-clothing',
                'parent_id' => 12,
            ],
            // kids shoes
            [
                'name' => 'کفش بچگانه',
                'slug' => 'kids-shoes-child-shoes',
                'parent_id' => 13,
            ],
            [
                'name' => 'کفش دخترانه',
                'slug' => 'kids-shoes-girl-shoes',
                'parent_id' => 13,
            ],
            [
                'name' => 'کفش پسرانه',
                'slug' => 'kids-shoes-boy-shoes',
                'parent_id' => 13,
            ],

            // kids accessories
            [
                'name' => 'اکسسوری بچگانه',
                'slug' => 'kids-accessories-child-accessories',
                'parent_id' => 14,
            ],
            [
                'name' => 'اکسسوری پسرانه',
                'slug' => 'kids-accessories-boy-accessories',
                'parent_id' => 14,
            ],
            [
                'name' => 'اکسسوری دخترانه',
                'slug' => 'kids-accessories-girl-accessories',
                'parent_id' => 14,
            ],
            // kids sports
            [
                'name' => 'ورزشی پسرانه',
                'slug' => 'kids-sports-boy-sport',
                'parent_id' => 15,
            ],
            [
                'name' => 'ورزشی دخترانه',
                'slug' => 'kids-sports-girl-sport',
                'parent_id' => 15,
            ],

        ];

        foreach ($values as $value) {
            Category::factory()->create([
                'name' => $value['name'],
                'slug' => $value['slug'],
                'parent_id' => $value['parent_id'],
            ]);
        }
    }
}
