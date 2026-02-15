<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Property;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add new columns
        Schema::table('reviews', function (Blueprint $table) {
            // Add polymorphic columns
            $table->unsignedBigInteger('reviewable_id')->after('user_id')->nullable();
            $table->string('reviewable_type')->after('reviewable_id')->nullable();
            
            // Add missing fields from the Review model
            $table->string('title')->after('reviewable_type')->nullable();
            $table->boolean('approved')->default(false)->after('comment');
            $table->string('moderation_status')->default('pending')->after('approved');
            $table->string('ip_address')->nullable()->after('moderation_status');
            $table->integer('helpful_votes')->default(0)->after('ip_address');
            $table->integer('unhelpful_votes')->default(0)->after('helpful_votes');
            
            // Add index for polymorphic relationship
            $table->index(['reviewable_id', 'reviewable_type']);
        });
        
        // Migrate existing data using Property::class for better maintainability
        DB::table('reviews')
            ->whereNotNull('property_id')
            ->update([
                'reviewable_id' => DB::raw('property_id'),
                'reviewable_type' => Property::class
            ]);
        
        // Make polymorphic columns non-nullable for data integrity
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('reviewable_id')->nullable(false)->change();
            $table->string('reviewable_type')->nullable(false)->change();
        });
        
        // Drop the foreign key and old property_id column
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
            $table->dropColumn('property_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Add back the property_id column
            $table->unsignedBigInteger('property_id')->after('user_id')->nullable();
        });
        
        // Migrate data back BEFORE dropping columns using Property::class for consistency
        DB::table('reviews')
            ->where('reviewable_type', Property::class)
            ->update([
                'property_id' => DB::raw('reviewable_id')
            ]);
        
        Schema::table('reviews', function (Blueprint $table) {
            // Drop polymorphic columns
            $table->dropIndex(['reviewable_id', 'reviewable_type']);
            $table->dropColumn(['reviewable_id', 'reviewable_type', 'title', 'approved', 'moderation_status', 'ip_address', 'helpful_votes', 'unhelpful_votes']);
            
            // Re-add foreign key
            $table->foreign('property_id')->references('id')->on('properties');
        });
    }
};
