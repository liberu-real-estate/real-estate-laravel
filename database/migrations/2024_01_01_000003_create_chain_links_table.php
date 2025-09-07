<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chain_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_chain_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('seller_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('position_in_chain');
            $table->string('link_type'); // sale, purchase, first_time_buyer, cash_buyer
            $table->string('status')->default('pending'); // pending, in_progress, completed, blocked, delayed
            $table->date('estimated_completion')->nullable();
            $table->date('actual_completion')->nullable();
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->boolean('mortgage_approved')->default(false);
            $table->boolean('survey_completed')->default(false);
            $table->string('legal_work_status')->default('not_started'); // not_started, in_progress, completed
            $table->date('exchange_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->text('notes')->nullable();
            $table->json('blocking_issues')->nullable();
            $table->timestamps();

            $table->index(['property_chain_id', 'position_in_chain']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chain_links');
    }
};