<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->integer('bedrooms')->nullable()->change();
            $table->integer('bathrooms')->nullable()->change();
            $table->year('year_built')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->integer('bedrooms')->nullable(false)->change();
            $table->integer('bathrooms')->nullable(false)->change();
            $table->year('year_built')->nullable(false)->change();
        });
    }
};
