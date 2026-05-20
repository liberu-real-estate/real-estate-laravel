<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_progressions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('stage', [
                'offer_accepted',
                'solicitors_instructed',
                'searches_ordered',
                'searches_received',
                'enquiries_raised',
                'enquiries_answered',
                'mortgage_offer_received',
                'exchange_ready',
                'exchanged',
                'completion_date_set',
                'completed',
            ])->default('offer_accepted');
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->date('offer_accepted_date')->nullable();
            $table->date('exchange_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('buyer_solicitor_name')->nullable();
            $table->string('buyer_solicitor_email')->nullable();
            $table->string('buyer_solicitor_phone')->nullable();
            $table->string('seller_solicitor_name')->nullable();
            $table->string('seller_solicitor_email')->nullable();
            $table->string('seller_solicitor_phone')->nullable();
            $table->string('mortgage_lender')->nullable();
            $table->string('mortgage_broker')->nullable();
            $table->json('checklist_items')->nullable()->comment('Array of checklist item states');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_progressions');
    }
};
