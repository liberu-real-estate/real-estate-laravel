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
        Schema::create('properties', function (Blueprint $table) {
            $table->id('id');
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->decimal('price', 10, 2);
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->integer('area_sqft');
            $table->year('year_built');
            $table->string('property_type');
            $table->string('status');
            $table->date('list_date')->default(now());
            $table->date('sold_date')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('team_id')->nullable()->default(null);
            $table->boolean('is_featured')->default(false);
            $table->string('rightmove_id')->nullable();
            $table->string('zoopla_id')->nullable();
            $table->string('onthemarket_id')->nullable();
            $table->string('energy_rating')->nullable();
            $table->integer('energy_score')->nullable();
            $table->date('energy_rating_date')->nullable();
            $table->json('smart_home_features')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('UK')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('virtual_tour_url')->nullable();
            $table->unsignedBigInteger('neighborhood_id')->nullable();
            $table->unsignedBigInteger('property_template_id')->nullable();
            $table->string('property_template')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('insurance_provider')->nullable();
            $table->string('insurance_policy_number')->nullable();
            $table->decimal('insurance_value', 10, 2)->nullable();
            $table->date('insurance_expiry_date')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('team_id')->references('id')->on('teams');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
