<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the 'digital_signatures' table in the database.
 */

class CreateDigitalSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations to create the table.
     */
    public function up()
    {
        Schema::create('digital_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('document_id')->constrained();
            $table->text('signature_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    /**
     * Reverse the migrations by dropping the 'digital_signatures' table.
     */
    public function down()
    {
        Schema::dropIfExists('digital_signatures');
    }
}
