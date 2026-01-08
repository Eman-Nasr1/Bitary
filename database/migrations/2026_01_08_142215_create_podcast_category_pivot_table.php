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
        Schema::create('podcast_category_pivot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('podcast_id')->constrained('podcasts')->cascadeOnDelete();
            $table->foreignId('podcast_category_id')->constrained('podcast_categories')->cascadeOnDelete();
            $table->unique(['podcast_id', 'podcast_category_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcast_category_pivot');
    }
};
