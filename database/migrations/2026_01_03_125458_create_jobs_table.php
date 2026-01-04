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
        Schema::dropIfExists('job_listings');
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialization_id')->constrained('job_specializations')->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            $table->string('title_ar');
            $table->string('title_en');
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->text('responsibilities_ar')->nullable();
            $table->text('responsibilities_en')->nullable();
            $table->text('qualifications_ar')->nullable();
            $table->text('qualifications_en')->nullable();
            $table->enum('apply_method', ['in_app', 'whatsapp', 'email', 'external_link'])->default('in_app');
            $table->string('whatsapp_number')->nullable();
            $table->string('email_address')->nullable();
            $table->string('external_link')->nullable();
            $table->enum('status', ['draft', 'pending', 'published', 'rejected', 'archived'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            $table->index('provider_id');
            $table->index('specialization_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
