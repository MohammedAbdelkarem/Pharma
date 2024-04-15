<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['Category' => 'Digestive'],
            ['Category' => 'Respiratory'],
            ['Category' => 'Optical'],
            ['Category' => 'Neurological'],
            ['Category' => 'Hormonal'],
            ['Category' => 'Vitamins'],
            ['Category' => 'Nasopharyngeal'],
            ['Category' => 'Rebroductive'],
            ['Category' => 'Pain_Killers'],
            ['Category' => 'Mascular'],
            ['Category' => 'Allergic'],
            ['Category' => 'Cardiovascular'],
        ];

        Category::insert($categories);
    }
}
