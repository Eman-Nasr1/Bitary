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
        Schema::create('provider_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('provider_type', ['doctor', 'clinic', 'pharmacy', 'company'])->default('doctor');
            $table->string('entity_name'); // Clinic/Company name
            $table->string('specialty')->nullable();
            $table->string('degree')->nullable();
            $table->string('phone');
            $table->string('whatsapp')->nullable();
            $table->string('email');
            $table->text('address');
            $table->text('google_maps_link')->nullable();
            $table->string('id_document')->nullable(); // File path
            $table->string('license_document')->nullable(); // File path
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
            
            // Note: Cannot use unique constraint on (user_id, status) because user can have
            // multiple requests with different statuses. Duplicate pending requests are
            // prevented in application logic (ProviderRequestService).
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_requests');
    }
};
