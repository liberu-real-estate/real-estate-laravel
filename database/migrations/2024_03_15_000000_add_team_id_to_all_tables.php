<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeamIdToAllTables extends Migration
{
    public function up()
    {
        $tables = [
            'appointments', 'bookings', 'branches', 'connected_accounts',
            'digital_signatures', 'documents', 'document_templates',
            'favorites', 'images', 'key_locations', 'properties',
            'property_features', 'reviews', 'transactions', 'users'
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
            'appointments', 'bookings', 'branches', 'connected_accounts',
            'digital_signatures', 'documents', 'document_templates',
            'favorites', 'images', 'key_locations', 'properties',
            'property_features', 'reviews', 'transactions', 'users'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['team_id']);
                $table->dropColumn('team_id');
            });
        }
    }
}