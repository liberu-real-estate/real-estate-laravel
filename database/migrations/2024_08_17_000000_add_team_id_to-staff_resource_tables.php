<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeamIdToStaffResourceTables extends Migration
{
    public function up()
    {
        $tables = [
            'appointments', 'bookings', 'buyers', 'contractors', 'document_templates',
            'favorites', 'images', 'key_locations', 'property_features', 'property_categories',
            'properties', 'reviews', 'tenants', 'transactions','leases','messages', 'documents',
            'email_campaigns', 'energy_consumptions', 'leads', 'lease_agreements', 'utility_payments',
            'property_templates', 'payments', 'maintenance_requests'
        ];

        foreach ($tables as $table) {
            if (!Schema::hasColumn($table, 'team_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->foreignId('team_id')->nullable()->constrained()->onDelete('cascade')->default(1);
                });
            }
        }
    }

    public function down()
    {
        $tables = [
            'appointments', 'buyers', 'bookings', 'contractors', 'document_templates',
            'favorites', 'images', 'key_locations', 'property_features',
            'properties', 'reviews', 'tenants', 'transactions', 'leases','messages', 'documents',
            'email_campaigns', 'energy_consumptions', 'leads', 'lease_agreements', 'utility_payments',
            'property_templates', 'payments', 'maintenance_requests'
        ];

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'team_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign(['team_id']);
                    $table->dropColumn('team_id');
                });
            }
        }
    }
}
