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
            $table->unsignedTinyInteger('walkability_score')->nullable()->after('longitude');
            $table->string('walkability_description')->nullable()->after('walkability_score');
            $table->unsignedTinyInteger('transit_score')->nullable()->after('walkability_description');
            $table->string('transit_description')->nullable()->after('transit_score');
            $table->unsignedTinyInteger('bike_score')->nullable()->after('transit_description');
            $table->string('bike_description')->nullable()->after('bike_score');
            $table->timestamp('walkability_updated_at')->nullable()->after('bike_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'walkability_score',
                'walkability_description',
                'transit_score',
                'transit_description',
                'bike_score',
                'bike_description',
                'walkability_updated_at'
            ]);
        });
    }
};
