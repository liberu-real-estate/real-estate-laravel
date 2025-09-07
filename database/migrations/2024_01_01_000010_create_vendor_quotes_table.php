<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->text('work_description');
            $table->decimal('quote_amount', 10, 2);
            $table->decimal('labor_cost', 10, 2)->nullable();
            $table->decimal('materials_cost', 10, 2)->nullable();
            $table->decimal('additional_costs', 10, 2)->nullable();
            $table->date('quote_date');
            $table->date('valid_until');
            $table->integer('estimated_duration')->nullable(); // in hours
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->string('status')->default('pending'); // pending, accepted, rejected, expired
            $table->text('notes')->nullable();
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->index(['vendor_id', 'status']);
            $table->index(['property_id', 'status']);
            $table->index(['quote_date', 'valid_until']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_quotes');
    }
};