<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PodcastCategory;

class PodcastCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name_ar' => 'تثقيف عام', 'name_en' => 'General Education'],
            ['name_ar' => 'أمراض', 'name_en' => 'Diseases'],
            ['name_ar' => 'مقابلات مع خبراء', 'name_en' => 'Expert Interviews'],
            ['name_ar' => 'قصص واقعية', 'name_en' => 'Real Stories'],
            ['name_ar' => 'تغذية', 'name_en' => 'Nutrition'],
            ['name_ar' => 'تربية حيوانات', 'name_en' => 'Animal Care'],
        ];

        foreach ($categories as $category) {
            PodcastCategory::create($category);
        }
    }
}
