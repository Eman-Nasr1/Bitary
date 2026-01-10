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
        Schema::create('market_prices', function (Blueprint $table) {
            $table->id();
            $table->string('product_name_ar');
            $table->string('product_name_en')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('currency')->default('EGP');
            $table->decimal('change_percent', 5, 2)->nullable();
            $table->enum('trend', ['up', 'down', 'stable'])->default('stable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_prices');
    }
};
