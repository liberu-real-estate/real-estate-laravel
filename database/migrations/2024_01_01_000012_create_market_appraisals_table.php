<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_appraisals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('appraisal_type'); // market_value, rental_assessment, investment_analysis
            $table->decimal('current_market_value', 12, 2)->nullable();
            $table->decimal('rental_value_monthly', 10, 2)->nullable();
            $table->decimal('rental_value_weekly', 10, 2)->nullable();
            $table->decimal('price_per_sqft', 8, 2)->nullable();
            $table->string('market_trend')->nullable(); // rising, stable, declining
            $table->string('demand_level')->nullable(); // very_high, high, medium, low, very_low
            $table->string('supply_level')->nullable(); // very_high, high, medium, low, very_low
            $table->integer('days_on_market_average')->nullable();
            $table->json('comparable_sales')->nullable();
            $table->json('market_factors')->nullable();
            $table->integer('location_score')->nullable(); // 1-100
            $table->integer('condition_score')->nullable(); // 1-100
            $table->integer('features_score')->nullable(); // 1-100
            $table->integer('overall_score')->nullable(); // 1-100
            $table->date('appraisal_date');
            $table->date('valid_until');
            $table->foreignId('appraiser_id')->constrained('users')->onDelete('cascade');
            $table->string('methodology')->nullable();
            $table->integer('confidence_level')->default(0); // 0-100
            $table->string('market_segment')->nullable();
            $table->json('target_buyer_profile')->nullable();
            $table->json('marketing_recommendations')->nullable();
            $table->text('pricing_strategy')->nullable();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['property_id', 'appraisal_type']);
            $table->index(['appraisal_date', 'valid_until']);
            $table->index(['overall_score', 'confidence_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_appraisals');
    }
};