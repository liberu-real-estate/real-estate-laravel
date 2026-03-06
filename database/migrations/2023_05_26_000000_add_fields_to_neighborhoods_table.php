<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToNeighborhoodsTable extends Migration
{
    public function up()
    {
        Schema::table('neighborhoods', function (Blueprint $table) {
            $cols = [
                'median_income' => fn($t) => $t->decimal('median_income', 10, 2)->nullable(),
                'population' => fn($t) => $t->integer('population')->nullable(),
                'walk_score' => fn($t) => $t->integer('walk_score')->nullable(),
                'transit_score' => fn($t) => $t->integer('transit_score')->nullable(),
                'last_updated' => fn($t) => $t->timestamp('last_updated')->nullable(),
            ];
            foreach ($cols as $col => $fn) {
                if (!Schema::hasColumn('neighborhoods', $col)) {
                    $fn($table);
                }
            }
        });
    }

    public function down()
    {
        Schema::table('neighborhoods', function (Blueprint $table) {
            $cols = array_filter(
                ['median_income', 'population', 'walk_score', 'transit_score', 'last_updated'],
                fn($c) => Schema::hasColumn('neighborhoods', $c)
            );
            if (!empty($cols)) $table->dropColumn(array_values($cols));
        });
    }
}
