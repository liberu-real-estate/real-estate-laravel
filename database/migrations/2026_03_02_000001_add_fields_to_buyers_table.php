<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buyers', function (Blueprint $table) {
            if (!Schema::hasColumn('buyers', 'first_name')) {
                $table->string('first_name')->nullable();
            }
            if (!Schema::hasColumn('buyers', 'last_name')) {
                $table->string('last_name')->nullable();
            }
            if (!Schema::hasColumn('buyers', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('buyers', 'team_id')) {
                $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('buyers', 'status')) {
                $table->string('status')->default('active');
            }
            if (!Schema::hasColumn('buyers', 'search_criteria')) {
                $table->json('search_criteria')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('buyers', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['first_name', 'last_name', 'user_id', 'team_id', 'status', 'search_criteria'] as $col) {
                if (Schema::hasColumn('buyers', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                try { $table->dropForeign(['user_id']); } catch (\Exception $e) {}
                try { $table->dropForeign(['team_id']); } catch (\Exception $e) {}
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
