<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'floor_plan_data')) {
                $table->json('floor_plan_data')->nullable();
            }
            if (!Schema::hasColumn('properties', 'floor_plan_image')) {
                $table->string('floor_plan_image')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            foreach (['floor_plan_data', 'floor_plan_image'] as $col) {
                if (Schema::hasColumn('properties', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
