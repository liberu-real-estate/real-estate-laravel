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
        Schema::create('vr_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('vr_provider')->default('threejs'); // threejs, babylonjs, aframe, mock
            $table->json('design_data'); // Furniture positions, colors, materials, etc.
            $table->json('room_layout')->nullable(); // Room dimensions and layout
            $table->json('furniture_items')->nullable(); // List of furniture with positions
            $table->json('materials')->nullable(); // Wall colors, floor materials, etc.
            $table->json('lighting')->nullable(); // Lighting configuration
            $table->string('thumbnail_path')->nullable();
            $table->string('vr_scene_url')->nullable(); // URL to VR scene file
            $table->boolean('is_public')->default(false);
            $table->boolean('is_template')->default(false);
            $table->string('style')->nullable(); // modern, traditional, minimalist, etc.
            $table->integer('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['property_id', 'user_id']);
            $table->index(['team_id', 'is_public']);
            $table->index('style');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vr_designs');
    }
};
