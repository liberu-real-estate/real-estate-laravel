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
                'holographic_tour_url' => fn($t) => $t->string('holographic_tour_url')->nullable(),
                'holographic_provider' => fn($t) => $t->string('holographic_provider')->nullable(),
                'holographic_metadata' => fn($t) => $t->json('holographic_metadata')->nullable(),
                'holographic_enabled' => fn($t) => $t->boolean('holographic_enabled')->default(false),
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
                ['holographic_tour_url', 'holographic_provider', 'holographic_metadata', 'holographic_enabled'],
                fn($c) => Schema::hasColumn('properties', $c)
            );
            if (!empty($cols)) $table->dropColumn(array_values($cols));
        });
    }
};
