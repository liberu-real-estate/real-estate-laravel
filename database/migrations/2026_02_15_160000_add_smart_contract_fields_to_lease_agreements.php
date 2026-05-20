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
        Schema::table('lease_agreements', function (Blueprint $table) {
            $table->string('smart_contract_address')->nullable()->after('is_signed');
            $table->string('contract_status')->default('pending')->after('smart_contract_address');
            $table->boolean('landlord_signed')->default(false)->after('contract_status');
            $table->boolean('tenant_signed')->default(false)->after('landlord_signed');
            $table->timestamp('contract_deployed_at')->nullable()->after('tenant_signed');
            $table->text('agreement_hash')->nullable()->after('contract_deployed_at');
            $table->string('blockchain_network')->nullable()->after('agreement_hash');
        });

        Schema::table('rental_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('rental_applications', 'smart_contract_address')) {
                $table->string('smart_contract_address')->nullable()->after('credit_report_status');
            }
            if (!Schema::hasColumn('rental_applications', 'rental_history_status')) {
                $table->string('rental_history_status')->nullable()->after('credit_report_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lease_agreements', function (Blueprint $table) {
            $table->dropColumn([
                'smart_contract_address',
                'contract_status',
                'landlord_signed',
                'tenant_signed',
                'contract_deployed_at',
                'agreement_hash',
                'blockchain_network',
            ]);
        });

        Schema::table('rental_applications', function (Blueprint $table) {
            if (Schema::hasColumn('rental_applications', 'smart_contract_address')) {
                $table->dropColumn('smart_contract_address');
            }
        });
    }
};
