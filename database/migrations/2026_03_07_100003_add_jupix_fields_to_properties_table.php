<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'jupix_id')) {
                $table->unsignedBigInteger('jupix_id')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('properties', 'last_synced_at')) {
                $table->timestamp('last_synced_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'jupix_id')) {
                $table->dropColumn('jupix_id');
            }
            if (Schema::hasColumn('properties', 'last_synced_at')) {
                $table->dropColumn('last_synced_at');
            }
        });
    }
};
