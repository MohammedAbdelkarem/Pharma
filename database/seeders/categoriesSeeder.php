<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Medicine;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class categoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    //     Medicine::create([
    //         'category_id' => '1',
    //         'name' => 'mname',
    //         'scientific_name' => 'sname',
    //         'manufacture_company' => 'mmname',
    //         'available_quantity' => '5',
    //         'E.d' => '12/5/2002',
    //         'price' => '2000 S.P',
    //         'photo' => 'path',
    // ]);
        $records = [
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

        // Insert records into the database
        Category::insert($records);
    }
}
