<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->decimal('match_score', 5, 2); // Overall match percentage
            $table->json('match_criteria')->nullable();
            $table->decimal('price_match', 5, 2)->nullable();
            $table->decimal('location_match', 5, 2)->nullable();
            $table->decimal('size_match', 5, 2)->nullable();
            $table->decimal('features_match', 5, 2)->nullable();
            $table->decimal('type_match', 5, 2)->nullable();
            $table->string('status')->default('active'); // active, dismissed, interested, viewed
            $table->boolean('viewed_by_buyer')->default(false);
            $table->integer('buyer_interest_level')->nullable(); // 1-5 scale
            $table->text('agent_notes')->nullable();
            $table->timestamp('match_date')->useCurrent();
            $table->timestamp('last_updated')->useCurrent();
            $table->boolean('auto_generated')->default(true);
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->index(['buyer_id', 'match_score']);
            $table->index(['property_id', 'match_score']);
            $table->index(['status', 'viewed_by_buyer']);
            $table->index(['match_date']);
            $table->unique(['buyer_id', 'property_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_matches');
    }
};