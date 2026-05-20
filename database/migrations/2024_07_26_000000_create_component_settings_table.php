<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('component_settings', function (Blueprint $table) {
            $table->id();
            $table->string('component_name')->unique();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('component_settings');
    }
};