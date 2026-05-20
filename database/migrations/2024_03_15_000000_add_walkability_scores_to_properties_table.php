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
                'walkability_score' => fn($t) => $t->unsignedTinyInteger('walkability_score')->nullable(),
                'walkability_description' => fn($t) => $t->string('walkability_description')->nullable(),
                'transit_score' => fn($t) => $t->unsignedTinyInteger('transit_score')->nullable(),
                'transit_description' => fn($t) => $t->string('transit_description')->nullable(),
                'bike_score' => fn($t) => $t->unsignedTinyInteger('bike_score')->nullable(),
                'bike_description' => fn($t) => $t->string('bike_description')->nullable(),
                'walkability_updated_at' => fn($t) => $t->timestamp('walkability_updated_at')->nullable(),
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
                ['walkability_score', 'walkability_description', 'transit_score', 'transit_description', 'bike_score', 'bike_description', 'walkability_updated_at'],
                fn($c) => Schema::hasColumn('properties', $c)
            );
            if (!empty($cols)) $table->dropColumn(array_values($cols));
        });
    }
};
