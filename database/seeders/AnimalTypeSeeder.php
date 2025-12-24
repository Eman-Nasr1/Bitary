<?php

namespace Database\Seeders;

use App\Models\AnimalType;
use Illuminate\Database\Seeder;

class AnimalTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $animalTypes = [
            [
                'name_en' => 'Pets',
                'name_ar' => 'الحيوانات الأليفة',
                'image' => 'animal_types/pets.png',
            ],
            [
                'name_en' => 'Poultry',
                'name_ar' => 'الدواجن',
                'image' => 'animal_types/poultry.png',
            ],
            [
                'name_en' => 'Farm animal',
                'name_ar' => 'المزارع',
                'image' => 'animal_types/farm_animal.png',
            ],
            [
                'name_en' => 'Equine',
                'name_ar' => 'الخيل',
                'image' => 'animal_types/equine.png',
            ],
        ];

        foreach ($animalTypes as $animalType) {
            AnimalType::create($animalType);
        }
    }
}
