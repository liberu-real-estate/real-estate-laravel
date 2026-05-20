<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compliance_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compliance_item_id')->constrained()->onDelete('cascade');
            $table->string('document_type'); // certificate, report, invoice, correspondence
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('upload_date')->useCurrent();
            $table->date('expiry_date')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['compliance_item_id', 'document_type']);
            $table->index(['expiry_date']);
            $table->index(['is_verified']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_documents');
    }
};