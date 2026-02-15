<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('event_type'); // price_change, status_change, sale, listing, update
            $table->text('description');
            $table->decimal('old_price', 12, 2)->nullable();
            $table->decimal('new_price', 12, 2)->nullable();
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->json('changes')->nullable(); // JSON field to store all changes
            $table->date('event_date');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            $table->index(['property_id', 'event_date']);
            $table->index(['property_id', 'event_type']);
            $table->index('event_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_histories');
    }
};
