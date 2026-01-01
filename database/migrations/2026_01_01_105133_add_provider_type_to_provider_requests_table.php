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
        Schema::table('provider_requests', function (Blueprint $table) {
            $table->enum('provider_type', ['doctor', 'clinic', 'pharmacy', 'company'])
                  ->default('doctor')
                  ->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provider_requests', function (Blueprint $table) {
            $table->dropColumn('provider_type');
        });
    }
};
