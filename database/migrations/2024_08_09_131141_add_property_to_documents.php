<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'property_id')) {
                $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('documents', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            foreach (['property_id', 'user_id'] as $col) {
                if (Schema::hasColumn('documents', $col)) {
                    try { $table->dropForeign([$col]); } catch (\Exception $e) {}
                    $table->dropColumn($col);
                }
            }
        });
    }
};
