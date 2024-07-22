<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('on_the_market_settings', function (Blueprint $table) {
            $table->id();
            $table->string('api_key');
            $table->string('base_uri');
            $table->string('sync_frequency')->default('daily');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('on_the_market_settings');
    }
};