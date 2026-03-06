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
                'virtual_tour_url' => fn($t) => $t->string('virtual_tour_url')->nullable(),
                'virtual_tour_provider' => fn($t) => $t->string('virtual_tour_provider')->nullable(),
                'virtual_tour_embed_code' => fn($t) => $t->text('virtual_tour_embed_code')->nullable(),
                'live_tour_available' => fn($t) => $t->boolean('live_tour_available')->default(false),
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
                ['virtual_tour_url', 'virtual_tour_provider', 'virtual_tour_embed_code', 'live_tour_available'],
                fn($c) => Schema::hasColumn('properties', $c)
            );
            if (!empty($cols)) $table->dropColumn(array_values($cols));
        });
    }
};
