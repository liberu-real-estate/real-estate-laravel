<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->string('room_number');
            $table->decimal('size', 8, 2);
            $table->decimal('rent', 10, 2);
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};