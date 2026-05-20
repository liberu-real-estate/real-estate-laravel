<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lease_agreements', function (Blueprint $table) {
            // Drop the incorrect FK that references users and add one that references tenants
            try {
                $table->dropForeign(['tenant_id']);
            } catch (\Throwable $e) {
                // FK may not exist
            }
        });

        Schema::table('lease_agreements', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });

        Schema::table('lease_agreements', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('lease_agreements', function (Blueprint $table) {
            try {
                $table->dropForeign(['tenant_id']);
            } catch (\Throwable $e) {
                // FK may not exist
            }
        });

        Schema::table('lease_agreements', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });

        Schema::table('lease_agreements', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });
    }
};
