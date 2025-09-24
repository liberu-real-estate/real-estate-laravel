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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('team_id')->default(1);
            $table->boolean('is_featured')->default(false);
            $table->string('rightmove_id')->nullable();
            $table->string('zoopla_id')->nullable();
            $table->string('onthemarket_id')->nullable();
            $table->string('energy_rating')->nullable();
            $table->integer('energy_score')->nullable();
            $table->date('energy_rating_date')->nullable();
            $table->json('smart_home_features')->nullable();
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
