<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->text('description');
            $table->string('work_type'); // maintenance, repair, improvement, inspection, etc.
            $table->integer('priority')->default(2); // 1-4 (low to critical)
            $table->string('status')->default('pending'); // pending, approved, scheduled, in_progress, completed, cancelled
            $table->timestamp('scheduled_date')->nullable();
            $table->timestamp('started_date')->nullable();
            $table->timestamp('completed_date')->nullable();
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->decimal('actual_cost', 10, 2)->nullable();
            $table->decimal('estimated_hours', 8, 2)->nullable();
            $table->decimal('actual_hours', 8, 2)->nullable();
            $table->decimal('materials_cost', 10, 2)->nullable();
            $table->decimal('labor_cost', 10, 2)->nullable();
            $table->boolean('emergency_job')->default(false);
            $table->boolean('requires_access')->default(true);
            $table->text('access_instructions')->nullable();
            $table->json('safety_requirements')->nullable();
            $table->text('completion_notes')->nullable();
            $table->integer('customer_satisfaction')->nullable(); // 1-5 rating
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('invoice_number')->nullable();
            $table->string('payment_status')->default('pending'); // pending, paid, overdue
            $table->timestamps();
            $table->softDeletes();

            $table->index(['property_id', 'status']);
            $table->index(['vendor_id', 'status']);
            $table->index(['scheduled_date']);
            $table->index(['priority', 'emergency_job']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};