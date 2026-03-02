<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buyers', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->after('address');
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete()->after('user_id');
            $table->string('status')->default('active')->after('team_id');
            $table->json('search_criteria')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('buyers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['team_id']);
            $table->dropColumn(['first_name', 'last_name', 'user_id', 'team_id', 'status', 'search_criteria']);
        });
    }
};
