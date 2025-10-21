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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();

            // الشخص اللي عمل التقييم
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Polymorphic relation
            $table->unsignedBigInteger('rateable_id');
            $table->string('rateable_type');

            // بيانات التقييم
            $table->unsignedTinyInteger('rating'); // من 1 ل 5 مثلا
            $table->text('comment')->nullable();

            $table->timestamps();

            // تحسين الأداء عند البحث
            $table->index(['rateable_id', 'rateable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
