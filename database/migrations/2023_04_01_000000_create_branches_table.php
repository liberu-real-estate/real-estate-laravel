<?php

/**
 * Migration for creating the branches table in the database.
 *
 * This migration file is responsible for creating the structure of the branches table
 * in the application's database, including its columns and data types.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('phone_number');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('branches');
    }



}
