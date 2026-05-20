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
        Schema::create('community_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('event_date');
            $table->dateTime('end_date')->nullable();
            $table->string('location');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('category')->nullable();
            $table->string('organizer')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('website_url')->nullable();
            $table->boolean('is_public')->default(true);
            $table->unsignedBigInteger('property_id')->nullable();
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('event_date');
            $table->index('property_id');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_events');
    }
};
