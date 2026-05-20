<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('work_order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->decimal('rating', 3, 1); // Overall rating 1-5
            $table->integer('quality_rating')->nullable(); // 1-5
            $table->integer('timeliness_rating')->nullable(); // 1-5
            $table->integer('communication_rating')->nullable(); // 1-5
            $table->integer('professionalism_rating')->nullable(); // 1-5
            $table->integer('value_rating')->nullable(); // 1-5
            $table->text('review_text')->nullable();
            $table->json('pros')->nullable();
            $table->json('cons')->nullable();
            $table->boolean('would_recommend')->default(true);
            $table->boolean('would_hire_again')->default(true);
            $table->date('review_date');
            $table->boolean('is_verified')->default(false);
            $table->integer('helpful_votes')->default(0);
            $table->timestamps();

            $table->index(['vendor_id', 'rating']);
            $table->index(['review_date']);
            $table->index(['is_verified']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_reviews');
    }
};