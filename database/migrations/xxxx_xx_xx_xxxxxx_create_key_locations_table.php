<?php

/**
 * Migration for creating the 'key_locations' table in the database.
 * Defines the schema for the 'key_locations' table.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeyLocationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('key_locations', function (Blueprint $table) {
            $table->id();
            $table->string('location_name');
            $table->string('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('key_locations');
    }
}
