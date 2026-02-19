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
            $table->string('holographic_tour_url')->nullable()->after('virtual_tour_url');
            $table->string('holographic_provider')->nullable()->after('holographic_tour_url');
            $table->json('holographic_metadata')->nullable()->after('holographic_provider');
            $table->boolean('holographic_enabled')->default(false)->after('holographic_metadata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'holographic_tour_url',
                'holographic_provider',
                'holographic_metadata',
                'holographic_enabled',
            ]);
        });
    }
};
