<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_valuations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('valuation_type'); // market, rental, insurance, mortgage
            $table->decimal('estimated_value', 12, 2)->nullable();
            $table->decimal('market_value', 12, 2)->nullable();
            $table->decimal('rental_value', 10, 2)->nullable();
            $table->date('valuation_date');
            $table->string('valuer_name')->nullable();
            $table->string('valuer_company')->nullable();
            $table->string('valuation_method')->nullable();
            $table->json('comparable_properties')->nullable();
            $table->text('market_conditions')->nullable();
            $table->text('property_condition')->nullable();
            $table->json('location_factors')->nullable();
            $table->text('notes')->nullable();
            $table->integer('confidence_level')->default(0); // 0-100
            $table->date('valid_until')->nullable();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('active'); // active, expired, superseded
            $table->timestamps();
            $table->softDeletes();

            $table->index(['property_id', 'valuation_type']);
            $table->index(['valuation_date', 'valid_until']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_valuations');
    }
};