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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_listings')->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            $table->string('full_name');
            $table->string('phone');
            $table->string('email');
            $table->string('current_location')->nullable();
            $table->text('cover_letter')->nullable();
            $table->text('extra_info')->nullable();
            $table->string('cv_file')->nullable();
            $table->enum('status', ['new', 'reviewed', 'accepted', 'rejected'])->default('new');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('job_id');
            $table->index('provider_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
