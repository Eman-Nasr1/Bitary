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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en');
            $table->enum('category', ['vets', 'students', 'breeders']);
            $table->enum('level', ['beginner', 'intermediate', 'advanced']);
            $table->enum('language', ['ar', 'en', 'mixed']);
            $table->text('overview_ar')->nullable();
            $table->text('overview_en')->nullable();
            $table->string('intro_video_url')->nullable();
            $table->text('intro_video_iframe')->nullable();
            $table->integer('duration_weeks')->nullable();
            $table->integer('hours_per_week')->nullable();
            $table->integer('days_per_week')->nullable();
            $table->boolean('certificate_available')->default(false);
            $table->enum('certificate_type', ['digital', 'physical'])->nullable();
            $table->boolean('is_free')->default(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->enum('payment_method', ['online', 'whatsapp'])->default('online');
            $table->string('whatsapp_join_link')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
