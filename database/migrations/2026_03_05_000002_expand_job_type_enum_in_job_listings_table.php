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
        DB::statement("
            ALTER TABLE job_listings
            MODIFY COLUMN job_type ENUM('full_time', 'part_time', 'remote', 'online') NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('job_listings')
            ->whereIn('job_type', ['remote', 'online'])
            ->update(['job_type' => 'part_time']);

        DB::statement("
            ALTER TABLE job_listings
            MODIFY COLUMN job_type ENUM('full_time', 'part_time') NULL
        ");
    }
};

