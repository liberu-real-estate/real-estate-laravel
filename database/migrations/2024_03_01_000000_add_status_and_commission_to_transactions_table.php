<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndCommissionToTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'status')) {
                $table->string('status')->default('pending');
            }
            if (!Schema::hasColumn('transactions', 'commission_amount')) {
                $table->decimal('commission_amount', 10, 2)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            foreach (['status', 'commission_amount'] as $col) {
                if (Schema::hasColumn('transactions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
}
