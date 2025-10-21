<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();

            // الاسم
            $table->string('name_en');
            $table->string('name_ar');

            // العنوان / الوصف القصير
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();

            // السعر والخصم والكمية
            $table->decimal('price', 10, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->integer('quantity')->default(0);

            // التقييم
            $table->unsignedTinyInteger('rate')->nullable();

            // الوصف
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();

            // الوزن والأبعاد
            $table->string('weight')->nullable();
            $table->string('dimensions')->nullable();

            // علاقات
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('sellers')->cascadeOnDelete();

            // نوع المنتج
            $table->string('product_type_en');
            $table->string('product_type_ar');

            // الشركة المصنعة
            $table->string('manufacturer_en')->nullable();
            $table->string('manufacturer_ar')->nullable();

            // سياسة الاسترجاع
            $table->text('return_policy_en')->nullable();
            $table->text('return_policy_ar')->nullable();

            // سياسة الاستبدال
            $table->text('exchange_policy_en')->nullable();
            $table->text('exchange_policy_ar')->nullable();

            $table->timestamps();
        });

        // جدول pivot
        Schema::create('animal_medicine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animals')->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_medicine');
        Schema::dropIfExists('medicines');
    }
};
