<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('currency');
            $table->string('default_language');
            $table->text('address');
            $table->string('country');
            $table->string('email');
            $table->decimal('sales_commission_percentage', 5, 2)->default(1.00);
            $table->decimal('lettings_commission_percentage', 5, 2)->default(8.00);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_settings');
    }
};