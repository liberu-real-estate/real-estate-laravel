<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            // Add team_id column if it doesn't exist
            if (!Schema::hasColumn('favorites', 'team_id')) {
                $table->unsignedBigInteger('team_id')->nullable()->after('property_id');
                $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            }
            
            // Add unique constraint to prevent duplicate favorites
            $table->unique(['user_id', 'property_id'], 'user_property_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropUnique('user_property_unique');
            
            if (Schema::hasColumn('favorites', 'team_id')) {
                $table->dropForeign(['team_id']);
                $table->dropColumn('team_id');
            }
        });
    }
};
