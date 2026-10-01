<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => "Men's",
                'description' => 'Fragrances selected for men.',
            ],
            [
                'name' => "Women's",
                'description' => 'Fragrances selected for women.',
            ],
            [
                'name' => 'Designer Perfumes',
                'description' => 'Designer and luxury fragrances.',
            ],
            [
                'name' => 'Arabic Perfumes',
                'description' => 'Arabic and Middle Eastern fragrances.',
            ],
            [
                'name' => 'Unisex',
                'description' => 'Fragrances suitable for all genders.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
