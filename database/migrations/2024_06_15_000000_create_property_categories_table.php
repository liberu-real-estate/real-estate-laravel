<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('property_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->unsignedBigInteger('property_category_id')->after('id')->nullable();
            $table->foreign('property_category_id')->references('id')->on('property_categories')->onDelete('set null')->default(1);
        });

    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['property_category_id']);
            $table->dropColumn('property_category_id');
        });

        Schema::dropIfExists('property_categories');
    }
};

