<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'category')) {
                $table->string('category')->nullable();
            }
            if (!Schema::hasColumn('leads', 'last_contacted_at')) {
                $table->timestamp('last_contacted_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('leads', 'last_contacted_at')) {
                $table->dropColumn('last_contacted_at');
            }
        });
    }
};
