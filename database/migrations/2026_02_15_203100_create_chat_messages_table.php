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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('chat_conversations')->onDelete('cascade');
            $table->text('message');
            $table->string('sender_type'); // user, bot, agent
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('metadata')->nullable(); // Store intent, confidence, etc.
            $table->timestamps();
            
            $table->index('conversation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
