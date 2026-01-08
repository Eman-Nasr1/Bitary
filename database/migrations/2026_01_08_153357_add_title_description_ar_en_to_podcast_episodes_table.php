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
        Schema::table('podcast_episodes', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['title', 'description']);
            
            // Add new title columns
            $table->string('title_ar')->after('instructor_id');
            $table->string('title_en')->nullable()->after('title_ar');
            
            // Add new description columns
            $table->text('description_ar')->after('title_en');
            $table->text('description_en')->nullable()->after('description_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('podcast_episodes', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['title_ar', 'title_en', 'description_ar', 'description_en']);
            
            // Restore old columns
            $table->string('title')->after('instructor_id');
            $table->text('description')->after('title');
        });
    }
};
