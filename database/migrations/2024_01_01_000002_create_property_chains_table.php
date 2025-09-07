<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_chains', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('active'); // active, completed, broken, cancelled
            $table->integer('chain_position')->default(1);
            $table->integer('total_chain_length')->default(1);
            $table->date('estimated_completion_date')->nullable();
            $table->date('actual_completion_date')->nullable();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('lead_agent_id')->constrained('users')->onDelete('cascade');
            $table->integer('priority_level')->default(2); // 1-4 (low to critical)
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'priority_level']);
            $table->index(['estimated_completion_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_chains');
    }
};