<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToNeighborhoodsTable extends Migration
{
    public function up()
    {
        Schema::table('neighborhoods', function (Blueprint $table) {
            $table->decimal('median_income', 10, 2)->nullable();
            $table->integer('population')->nullable();
            $table->integer('walk_score')->nullable();
            $table->integer('transit_score')->nullable();
            $table->timestamp('last_updated')->nullable();
        });
    }

    public function down()
    {
        Schema::table('neighborhoods', function (Blueprint $table) {
            $table->dropColumn(['median_income', 'population', 'walk_score', 'transit_score', 'last_updated']);
        });
    }
}