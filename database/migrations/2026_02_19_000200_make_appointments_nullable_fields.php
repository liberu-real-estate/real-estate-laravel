<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id')->nullable()->change();
            $table->unsignedBigInteger('property_id')->nullable()->change();
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id')->nullable(false)->change();
            $table->unsignedBigInteger('property_id')->nullable(false)->change();
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
