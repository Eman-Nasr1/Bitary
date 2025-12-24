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
        Schema::table('users', function (Blueprint $table) {
            $table->string('family_name')->nullable()->after('name');
            $table->integer('age')->nullable()->after('family_name');
            $table->enum('gender', ['male', 'female'])->nullable()->after('age');
            $table->unsignedBigInteger('city_id')->nullable()->after('gender');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
            $table->text('address')->nullable()->after('city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropColumn(['family_name', 'age', 'gender', 'city_id', 'address']);
        });
    }
};
