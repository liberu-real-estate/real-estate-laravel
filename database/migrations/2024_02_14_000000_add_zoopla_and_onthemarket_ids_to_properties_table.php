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
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'zoopla_id')) {
                $table->string('zoopla_id')->nullable();
            }
            if (!Schema::hasColumn('properties', 'onthemarket_id')) {
                $table->string('onthemarket_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['zoopla_id', 'onthemarket_id']);
        });
    }
};