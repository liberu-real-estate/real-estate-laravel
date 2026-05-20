<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('reviewable_id')->nullable();
            $table->string('reviewable_type')->nullable();
            $table->string('title')->nullable();
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->date('review_date')->nullable();
            $table->boolean('approved')->default(false);
            $table->string('moderation_status')->default('pending');
            $table->string('ip_address')->nullable();
            $table->integer('helpful_votes')->default(0);
            $table->integer('unhelpful_votes')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
