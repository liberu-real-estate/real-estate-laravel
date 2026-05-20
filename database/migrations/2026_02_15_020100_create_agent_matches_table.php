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
        Schema::create('agent_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->decimal('match_score', 5, 2);
            $table->decimal('expertise_score', 5, 2)->nullable();
            $table->decimal('performance_score', 5, 2)->nullable();
            $table->decimal('availability_score', 5, 2)->nullable();
            $table->decimal('location_score', 5, 2)->nullable();
            $table->decimal('specialization_score', 5, 2)->nullable();
            $table->json('match_reasons')->nullable();
            $table->boolean('auto_generated')->default(true);
            $table->string('status')->default('pending'); // pending, accepted, rejected
            $table->timestamps();
            
            $table->unique(['user_id', 'agent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_matches');
    }
};
