<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable();
            $table->integer('size')->nullable();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_signable')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
