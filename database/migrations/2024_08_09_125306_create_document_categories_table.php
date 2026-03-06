<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('document_categories')) {
            Schema::create('document_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('description', 512)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('document_document_category')) {
            Schema::create('document_document_category', function (Blueprint $table) {
                $table->id();
                $table->foreignId('document_id')->constrained()->onDelete('cascade');
                $table->foreignId('document_category_id')->constrained()->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('document_document_category');
        Schema::dropIfExists('document_categories');
    }
};
