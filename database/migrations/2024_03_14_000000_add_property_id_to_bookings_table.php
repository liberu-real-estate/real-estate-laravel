<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'property_id')) {
                $table->unsignedBigInteger('property_id')->nullable();
                $table->foreign('property_id')->references('id')->on('properties')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'property_id')) {
                $table->dropForeign(['property_id']);
                $table->dropColumn('property_id');
            }
        });
    }
};
