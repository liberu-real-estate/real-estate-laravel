<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_order_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->string('update_type'); // status_change, progress, issue, completion
            $table->string('status_change')->nullable();
            $table->text('description');
            $table->integer('progress_percentage')->default(0);
            $table->decimal('time_spent', 8, 2)->nullable();
            $table->json('materials_used')->nullable();
            $table->json('issues_encountered')->nullable();
            $table->text('next_steps')->nullable();
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('update_date')->useCurrent();
            $table->boolean('is_customer_visible')->default(true);
            $table->timestamps();

            $table->index(['work_order_id', 'update_type']);
            $table->index(['update_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_order_updates');
    }
};