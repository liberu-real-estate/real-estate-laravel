<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compliance_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('compliance_type'); // epc, gas_safety, electrical, fire_safety, legionella, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('regulation_reference')->nullable();
            $table->date('required_by_date');
            $table->date('completed_date')->nullable();
            $table->string('status')->default('pending'); // pending, in_progress, completed, overdue, not_applicable
            $table->integer('priority_level')->default(2); // 1-4 (low to critical)
            $table->string('responsible_party')->nullable();
            $table->decimal('cost_estimate', 10, 2)->nullable();
            $table->decimal('actual_cost', 10, 2)->nullable();
            $table->string('certificate_number')->nullable();
            $table->date('certificate_expiry')->nullable();
            $table->boolean('renewal_required')->default(false);
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->integer('risk_level')->default(2); // 1-4 (low to critical)
            $table->timestamps();
            $table->softDeletes();

            $table->index(['property_id', 'compliance_type']);
            $table->index(['status', 'priority_level']);
            $table->index(['required_by_date']);
            $table->index(['certificate_expiry']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_items');
    }
};