<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToPropertiesTable extends Migration
{
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->index('price');
            $table->index('bedrooms');
            $table->index('bathrooms');
            $table->index('area_sqft');
            $table->index('property_type');
            $table->index('status');
            $table->index('is_featured');
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropIndex(['price']);
            $table->dropIndex(['bedrooms']);
            $table->dropIndex(['bathrooms']);
            $table->dropIndex(['area_sqft']);
            $table->dropIndex(['property_type']);
            $table->dropIndex(['status']);
            $table->dropIndex(['is_featured']);
        });
    }
}