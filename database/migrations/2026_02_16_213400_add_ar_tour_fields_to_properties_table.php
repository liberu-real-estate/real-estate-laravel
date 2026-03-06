<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $cols = [
                'ar_tour_enabled' => fn($t) => $t->boolean('ar_tour_enabled')->default(false),
                'ar_tour_settings' => fn($t) => $t->text('ar_tour_settings')->nullable(),
                'ar_placement_guide' => fn($t) => $t->string('ar_placement_guide')->nullable(),
                'ar_model_scale' => fn($t) => $t->float('ar_model_scale')->default(1.0),
            ];
            foreach ($cols as $col => $fn) {
                if (!Schema::hasColumn('properties', $col)) {
                    $fn($table);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $cols = array_filter(
                ['ar_tour_enabled', 'ar_tour_settings', 'ar_placement_guide', 'ar_model_scale'],
                fn($c) => Schema::hasColumn('properties', $c)
            );
            if (!empty($cols)) $table->dropColumn(array_values($cols));
        });
    }
};
