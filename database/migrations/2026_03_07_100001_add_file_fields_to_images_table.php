<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('images', function (Blueprint $table) {
            if (!Schema::hasColumn('images', 'file_path')) {
                $table->string('file_path')->nullable();
            }
            if (!Schema::hasColumn('images', 'file_name')) {
                $table->string('file_name')->nullable();
            }
            if (!Schema::hasColumn('images', 'mime_type')) {
                $table->string('mime_type')->nullable();
            }
            if (!Schema::hasColumn('images', 'is_staged')) {
                $table->boolean('is_staged')->default(false);
            }
            if (!Schema::hasColumn('images', 'original_image_id')) {
                $table->unsignedBigInteger('original_image_id')->nullable();
                $table->foreign('original_image_id')->references('image_id')->on('images')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $columns = ['original_image_id', 'is_staged', 'mime_type', 'file_name', 'file_path'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('images', $column)) {
                    if ($column === 'original_image_id') {
                        $table->dropForeign(['original_image_id']);
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};
