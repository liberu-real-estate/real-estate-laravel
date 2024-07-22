<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $tables = [
            'activities', 'appointments', 'bookings', 'branches', 'contractors',
            'digital_signatures', 'documents', 'document_templates', 'favorites',
            'images', 'key_locations', 'leads', 'properties', 'property_features',
            'reviews', 'site_settings', 'tenants', 'transactions', 'zoopla_settings'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->constrained()->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        $tables = [
            'activities', 'appointments', 'bookings', 'branches', 'contractors',
            'digital_signatures', 'documents', 'document_templates', 'favorites',
            'images', 'key_locations', 'leads', 'properties', 'property_features',
            'reviews', 'site_settings', 'tenants', 'transactions', 'zoopla_settings'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['team_id']);
                $table->dropColumn('team_id');
            });
        }
    }
};