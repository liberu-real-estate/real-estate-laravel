<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('viewing_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id')->nullable()->comment('References appointments primary key');
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('viewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('viewer_name')->nullable();
            $table->string('viewer_email')->nullable();
            $table->tinyInteger('overall_rating')->nullable()->comment('1-5 star rating');
            $table->tinyInteger('price_rating')->nullable()->comment('1-5: represents value for money');
            $table->tinyInteger('condition_rating')->nullable()->comment('1-5: condition of the property');
            $table->tinyInteger('location_rating')->nullable()->comment('1-5: location satisfaction');
            $table->tinyInteger('size_rating')->nullable()->comment('1-5: size satisfaction');
            $table->text('positive_comments')->nullable();
            $table->text('negative_comments')->nullable();
            $table->text('general_comments')->nullable();
            $table->enum('interest_level', ['very_interested', 'interested', 'neutral', 'not_interested', 'definitely_not'])->nullable();
            $table->boolean('would_make_offer')->default(false);
            $table->decimal('offer_price', 12, 2)->nullable();
            $table->string('token')->unique()->nullable()->comment('Used for unauthenticated feedback link');
            $table->timestamp('feedback_requested_at')->nullable();
            $table->timestamp('feedback_submitted_at')->nullable();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viewing_feedbacks');
    }
};
