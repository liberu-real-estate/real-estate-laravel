<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('right_move_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('right_move_settings', 'team_id')) {
                $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('right_move_settings', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('right_move_settings', 'channel')) {
                $table->enum('channel', ['sales', 'lettings'])->default('sales');
            }
            if (!Schema::hasColumn('right_move_settings', 'feed_type')) {
                $table->enum('feed_type', ['full', 'incremental'])->default('incremental');
            }
            if (!Schema::hasColumn('right_move_settings', 'feed_url')) {
                $table->string('feed_url')->nullable();
            }
            if (!Schema::hasColumn('right_move_settings', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('right_move_settings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('branch_id');
            $table->dropConstrainedForeignId('team_id');
            $table->dropColumn(['channel', 'feed_type', 'feed_url', 'is_active']);
        });
    }
};
