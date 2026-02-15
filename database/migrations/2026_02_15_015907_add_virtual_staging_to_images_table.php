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
        Schema::table('images', function (Blueprint $table) {
            $table->boolean('is_staged')->default(false)->after('property_id');
            $table->unsignedBigInteger('original_image_id')->nullable()->after('is_staged');
            $table->foreign('original_image_id')->references('image_id')->on('images')->onDelete('cascade');
            $table->string('staging_style')->nullable()->after('original_image_id');
            $table->json('staging_metadata')->nullable()->after('staging_style');
            $table->string('staging_provider')->nullable()->default('mock')->after('staging_metadata');
            $table->string('file_path')->nullable()->after('staging_provider');
            $table->string('file_name')->nullable()->after('file_path');
            $table->string('mime_type')->nullable()->after('file_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropForeign(['original_image_id']);
            $table->dropColumn([
                'is_staged',
                'original_image_id',
                'staging_style',
                'staging_metadata',
                'staging_provider',
                'file_path',
                'file_name',
                'mime_type',
            ]);
        });
    }
};
