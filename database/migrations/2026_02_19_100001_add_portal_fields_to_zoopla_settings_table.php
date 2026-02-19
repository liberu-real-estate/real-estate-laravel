<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('zoopla_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('zoopla_settings', 'feed_id')) {
                $table->string('feed_id')->nullable();
            }
            if (!Schema::hasColumn('zoopla_settings', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });

        // Change sync_frequency from integer to string if needed
        if (Schema::hasColumn('zoopla_settings', 'sync_frequency')) {
            Schema::table('zoopla_settings', function (Blueprint $table) {
                $table->string('sync_frequency')->default('daily')->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('zoopla_settings', function (Blueprint $table) {
            $table->dropColumn(['feed_id', 'is_active']);
            $table->integer('sync_frequency')->default(24)->change();
        });
    }
};
