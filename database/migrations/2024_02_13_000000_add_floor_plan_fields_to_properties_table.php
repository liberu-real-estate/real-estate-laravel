<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->json('floor_plan_data')->nullable();
            $table->string('floor_plan_image')->nullable();
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['floor_plan_data', 'floor_plan_image']);
        });
    }
};