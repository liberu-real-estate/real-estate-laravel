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
            // Check if virtual_tour_url exists, if not create it first
            if (!Schema::hasColumn('properties', 'virtual_tour_url')) {
                $table->string('virtual_tour_url')->nullable();
            }
            $table->string('virtual_tour_provider')->nullable()->after('virtual_tour_url');
            $table->text('virtual_tour_embed_code')->nullable()->after('virtual_tour_provider');
            $table->boolean('live_tour_available')->default(false)->after('virtual_tour_embed_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['virtual_tour_provider', 'virtual_tour_embed_code', 'live_tour_available']);
        });
    }
};
