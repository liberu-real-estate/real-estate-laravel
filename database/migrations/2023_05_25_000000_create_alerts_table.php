<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type')->nullable();
            $table->string('frequency')->nullable();
            $table->json('criteria')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('property_type')->nullable();
            $table->decimal('min_price', 10, 2)->nullable();
            $table->decimal('max_price', 10, 2)->nullable();
            $table->integer('min_bedrooms')->nullable();
            $table->integer('max_bedrooms')->nullable();
            $table->string('location')->nullable();
            $table->string('notification_frequency')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
