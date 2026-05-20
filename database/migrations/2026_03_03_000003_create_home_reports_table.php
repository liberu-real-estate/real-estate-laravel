<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->string('report_type')->default('scottish_home_report');
            $table->string('surveyor_name')->nullable();
            $table->string('surveyor_company')->nullable();
            $table->date('survey_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('energy_band', ['A', 'B', 'C', 'D', 'E', 'F', 'G'])->nullable();
            $table->integer('energy_current_score')->nullable();
            $table->integer('energy_potential_score')->nullable();
            $table->enum('property_condition', ['1', '2', '3'])->nullable()
                ->comment('1=No action required, 2=Routine maintenance, 3=Urgent attention');
            $table->json('condition_categories')->nullable()
                ->comment('Condition ratings for each section of the property');
            $table->decimal('market_value', 12, 2)->nullable();
            $table->decimal('reinstatement_cost', 12, 2)->nullable();
            $table->string('file_path')->nullable()->comment('Path to uploaded report PDF');
            $table->string('file_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_reports');
    }
};
