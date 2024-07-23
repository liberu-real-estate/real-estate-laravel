<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNeighborhoodsTable extends Migration
{
    public function up()
    {
        Schema::create('neighborhoods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->json('schools');
            $table->json('amenities');
            $table->string('crime_rate');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('neighborhoods');
    }
}