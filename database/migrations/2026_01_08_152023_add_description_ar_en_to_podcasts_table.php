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
        Schema::table('podcasts', function (Blueprint $table) {
            // Drop old description column
            $table->dropColumn('description');
            
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
        Schema::table('podcasts', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['description_ar', 'description_en']);
            
            // Restore old description column
            $table->text('description')->after('title_en');
        });
    }
};
