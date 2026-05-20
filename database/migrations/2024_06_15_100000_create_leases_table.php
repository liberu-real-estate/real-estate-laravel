<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('users')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('rent_amount', 10, 2);
            $table->string('status');
            $table->string('smart_contract_address')->nullable();
            $table->decimal('security_deposit', 10, 2)->nullable();
            $table->text('terms')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leases');
    }
};