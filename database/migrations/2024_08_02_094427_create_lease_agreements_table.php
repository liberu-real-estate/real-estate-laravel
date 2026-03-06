<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lease_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('landlord_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('monthly_rent', 10, 2)->nullable();
            $table->decimal('rent_amount', 10, 2)->nullable();
            $table->decimal('security_deposit', 10, 2)->nullable();
            $table->string('status')->default('draft');
            $table->string('payment_frequency')->nullable();
            $table->text('terms')->nullable();
            $table->text('content')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->boolean('is_signed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lease_agreements');
    }
};
