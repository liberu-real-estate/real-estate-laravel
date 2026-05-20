<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rental_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tenant_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('pending');
            $table->string('employment_status')->nullable();
            $table->decimal('annual_income', 10, 2)->nullable();
            $table->decimal('monthly_income', 10, 2)->nullable();
            $table->date('application_date')->nullable();
            $table->date('desired_move_in_date')->nullable();
            $table->string('background_check_status')->nullable();
            $table->string('credit_report_status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rental_applications');
    }
};
