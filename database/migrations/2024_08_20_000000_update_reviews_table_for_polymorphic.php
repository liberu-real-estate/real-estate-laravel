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
        Schema::table('reviews', function (Blueprint $table) {
            // Drop the existing foreign key and property_id column
            $table->dropForeign(['property_id']);
            
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
        
        // Migrate existing data
        DB::statement("UPDATE reviews SET reviewable_id = property_id, reviewable_type = 'App\\\\Models\\\\Property' WHERE property_id IS NOT NULL");
        
        // Now drop the old property_id column
        Schema::table('reviews', function (Blueprint $table) {
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
            
            // Migrate data back
            DB::statement("UPDATE reviews SET property_id = reviewable_id WHERE reviewable_type = 'App\\\\Models\\\\Property'");
            
            // Drop polymorphic columns
            $table->dropIndex(['reviewable_id', 'reviewable_type']);
            $table->dropColumn(['reviewable_id', 'reviewable_type', 'title', 'approved', 'moderation_status', 'ip_address', 'helpful_votes', 'unhelpful_votes']);
            
            // Re-add foreign key
            $table->foreign('property_id')->references('id')->on('properties');
        });
    }
};
