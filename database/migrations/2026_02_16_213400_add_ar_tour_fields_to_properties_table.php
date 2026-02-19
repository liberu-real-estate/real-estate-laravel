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
            // Check if model_3d_url exists, otherwise add after virtual_tour_url
            if (Schema::hasColumn('properties', 'model_3d_url')) {
                $table->boolean('ar_tour_enabled')->default(false)->after('model_3d_url');
            } else {
                $table->boolean('ar_tour_enabled')->default(false);
            }
            $table->text('ar_tour_settings')->nullable()->after('ar_tour_enabled');
            $table->string('ar_placement_guide')->nullable()->after('ar_tour_settings');
            $table->float('ar_model_scale')->default(1.0)->after('ar_placement_guide');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['ar_tour_enabled', 'ar_tour_settings', 'ar_placement_guide', 'ar_model_scale']);
        });
    }
};
