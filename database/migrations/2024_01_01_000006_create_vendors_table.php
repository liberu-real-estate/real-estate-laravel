<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('UK');
            $table->string('website')->nullable();
            $table->string('vendor_type'); // plumber, electrician, cleaner, gardener, etc.
            $table->json('specializations')->nullable();
            $table->decimal('rating', 3, 1)->default(0);
            $table->string('status')->default('active'); // active, inactive, blacklisted
            $table->boolean('preferred_vendor')->default(false);
            $table->date('insurance_valid_until')->nullable();
            $table->json('certifications')->nullable();
            $table->string('payment_terms')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->decimal('daily_rate', 8, 2)->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->json('availability_hours')->nullable();
            $table->json('service_areas')->nullable();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->string('tax_number')->nullable();
            $table->json('bank_details')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['vendor_type', 'status']);
            $table->index(['preferred_vendor']);
            $table->index(['rating']);
            $table->index(['insurance_valid_until']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};