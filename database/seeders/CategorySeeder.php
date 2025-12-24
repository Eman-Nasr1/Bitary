<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name_en' => 'Medicines',
                'name_ar' => 'أدوية',
                'image' => 'categories/medicines.png',
            ],
            [
                'name_en' => 'Supplements/Vitamins',
                'name_ar' => 'مكملات / فيتامينات',
                'image' => 'categories/supplements.png',
            ],
            [
                'name_en' => 'Food',
                'name_ar' => 'طعام',
                'image' => 'categories/food.png',
            ],
            [
                'name_en' => 'Accessories',
                'name_ar' => 'إكسسوارات',
                'image' => 'categories/accessories.png',
            ],
            [
                'name_en' => 'Hygiene',
                'name_ar' => 'أدوات نظافة',
                'image' => 'categories/hygiene.png',
            ],
            [
                'name_en' => 'Toys',
                'name_ar' => 'ألعاب',
                'image' => 'categories/toys.png',
            ],
            [
                'name_en' => 'Medical Supplies',
                'name_ar' => 'مستلزمات طبية',
                'image' => 'categories/medical_supplies.png',
            ],
            [
                'name_en' => 'Anti-parasitic',
                'name_ar' => 'مكافحة طفيليات',
                'image' => 'categories/anti_parasitic.png',
            ],
            [
                'name_en' => 'Grooming',
                'name_ar' => 'شامبو/عناية جلد وشعر',
                'image' => 'categories/grooming.png',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
