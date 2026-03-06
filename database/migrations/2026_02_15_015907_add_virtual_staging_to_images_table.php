<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $cols = [
                'is_virtually_staged' => fn($t) => $t->boolean('is_virtually_staged')->default(false),
                'original_image_path' => fn($t) => $t->string('original_image_path')->nullable(),
                'staging_style' => fn($t) => $t->string('staging_style')->nullable(),
                'staging_provider' => fn($t) => $t->string('staging_provider')->nullable(),
                'staging_job_id' => fn($t) => $t->string('staging_job_id')->nullable(),
                'staging_status' => fn($t) => $t->string('staging_status')->nullable(),
                'staging_metadata' => fn($t) => $t->json('staging_metadata')->nullable(),
                'staged_at' => fn($t) => $t->timestamp('staged_at')->nullable(),
            ];
            foreach ($cols as $col => $fn) {
                if (!Schema::hasColumn('images', $col)) {
                    $fn($table);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $cols = array_filter(
                ['is_virtually_staged', 'original_image_path', 'staging_style', 'staging_provider', 'staging_job_id', 'staging_status', 'staging_metadata', 'staged_at'],
                fn($c) => Schema::hasColumn('images', $c)
            );
            if (!empty($cols)) $table->dropColumn(array_values($cols));
        });
    }
};
