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
        Schema::create('smart_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_address')->unique();
            $table->string('contract_type')->default('rental_agreement');
            $table->foreignId('lease_agreement_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('landlord_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            
            // Contract parameters
            $table->decimal('rent_amount', 10, 2);
            $table->decimal('security_deposit', 10, 2)->default(0);
            $table->timestamp('lease_start_date');
            $table->timestamp('lease_end_date');
            
            // Contract state
            $table->string('status')->default('pending'); // pending, active, completed, terminated
            $table->boolean('landlord_signed')->default(false);
            $table->boolean('tenant_signed')->default(false);
            $table->timestamp('deployed_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('terminated_at')->nullable();
            
            // Blockchain data
            $table->string('blockchain_network')->default('simulated'); // ethereum, polygon, simulated
            $table->text('transaction_hash')->nullable();
            $table->text('agreement_hash')->nullable();
            $table->json('abi')->nullable();
            $table->text('bytecode')->nullable();
            
            // Tracking
            $table->decimal('total_rent_paid', 10, 2)->default(0);
            $table->timestamp('last_rent_payment')->nullable();
            $table->integer('rent_payments_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['contract_address', 'status']);
            $table->index(['lease_agreement_id']);
            $table->index(['property_id']);
        });

        Schema::create('smart_contract_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('smart_contract_id')->constrained()->onDelete('cascade');
            $table->string('transaction_type'); // deploy, sign, rent_payment, terminate, maintenance
            $table->string('transaction_hash')->nullable();
            $table->foreignId('initiated_by')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->string('status')->default('pending'); // pending, confirmed, failed
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            
            $table->index(['smart_contract_id', 'transaction_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smart_contract_transactions');
        Schema::dropIfExists('smart_contracts');
    }
};
