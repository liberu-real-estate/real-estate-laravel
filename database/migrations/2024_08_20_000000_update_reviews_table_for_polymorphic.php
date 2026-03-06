<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Property;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'reviewable_id')) {
                $table->unsignedBigInteger('reviewable_id')->nullable();
            }
            if (!Schema::hasColumn('reviews', 'reviewable_type')) {
                $table->string('reviewable_type')->nullable();
            }
            if (!Schema::hasColumn('reviews', 'title')) {
                $table->string('title')->nullable();
            }
            if (!Schema::hasColumn('reviews', 'approved')) {
                $table->boolean('approved')->default(false);
            }
            if (!Schema::hasColumn('reviews', 'moderation_status')) {
                $table->string('moderation_status')->default('pending');
            }
            if (!Schema::hasColumn('reviews', 'ip_address')) {
                $table->string('ip_address')->nullable();
            }
            if (!Schema::hasColumn('reviews', 'helpful_votes')) {
                $table->integer('helpful_votes')->default(0);
            }
            if (!Schema::hasColumn('reviews', 'unhelpful_votes')) {
                $table->integer('unhelpful_votes')->default(0);
            }
        });

        // Migrate existing data if property_id exists and reviewable_id is empty
        if (Schema::hasColumn('reviews', 'property_id')) {
            DB::table('reviews')
                ->whereNotNull('property_id')
                ->whereNull('reviewable_id')
                ->update([
                    'reviewable_id' => DB::raw('property_id'),
                    'reviewable_type' => Property::class
                ]);

            Schema::table('reviews', function (Blueprint $table) {
                try { $table->dropForeign(['property_id']); } catch (\Exception $e) {}
                $table->dropColumn('property_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            foreach (['reviewable_id', 'reviewable_type', 'title', 'approved', 'moderation_status', 'ip_address', 'helpful_votes', 'unhelpful_votes'] as $col) {
                if (Schema::hasColumn('reviews', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
