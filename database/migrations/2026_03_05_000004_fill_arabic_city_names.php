<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $arabicNames = [
            'Cairo' => 'القاهرة',
            'Giza' => 'الجيزة',
            'Alexandria' => 'الإسكندرية',
            'Dakahlia' => 'الدقهلية',
            'Red Sea' => 'البحر الأحمر',
            'Beheira' => 'البحيرة',
            'Fayoum' => 'الفيوم',
            'Gharbiya' => 'الغربية',
            'Ismailia' => 'الإسماعيلية',
            'Menofia' => 'المنوفية',
            'Monufia' => 'المنوفية',
            'Minya' => 'المنيا',
            'Qalyubia' => 'القليوبية',
            'New Valley' => 'الوادي الجديد',
            'Suez' => 'السويس',
            'Aswan' => 'أسوان',
            'Assiut' => 'أسيوط',
            'Beni Suef' => 'بني سويف',
            'Port Said' => 'بورسعيد',
            'Damietta' => 'دمياط',
            'Sharkia' => 'الشرقية',
            'South Sinai' => 'جنوب سيناء',
            'Kafr El Sheikh' => 'كفر الشيخ',
            'Matrouh' => 'مطروح',
            'Luxor' => 'الأقصر',
            'Qena' => 'قنا',
            'North Sinai' => 'شمال سيناء',
            'Sohag' => 'سوهاج',
        ];

        foreach ($arabicNames as $english => $arabic) {
            DB::table('cities')
                ->where(function ($query) use ($english) {
                    $query->where('name_en', $english)
                        ->orWhere('name', $english);
                })
                ->where(function ($query) {
                    $query->whereNull('name_ar')
                        ->orWhereColumn('name_ar', 'name_en')
                        ->orWhereColumn('name_ar', 'name');
                })
                ->update(['name_ar' => $arabic]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No destructive rollback for translated content.
    }
};

