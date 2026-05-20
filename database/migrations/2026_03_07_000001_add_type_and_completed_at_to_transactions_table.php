<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'transaction_type')) {
                $table->string('transaction_type')->default('sale')->after('amount');
            }
            if (!Schema::hasColumn('transactions', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('transaction_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            foreach (['transaction_type', 'completed_at'] as $col) {
                if (Schema::hasColumn('transactions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
