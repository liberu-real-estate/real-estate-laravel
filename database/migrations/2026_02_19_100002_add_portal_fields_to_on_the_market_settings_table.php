<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('on_the_market_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('on_the_market_settings', 'team_id')) {
                $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('on_the_market_settings', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('on_the_market_settings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('team_id');
            $table->dropColumn(['is_active']);
        });
    }
};
