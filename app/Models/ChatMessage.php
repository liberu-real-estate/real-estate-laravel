<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'message',
        'sender_type',
        'sender_id',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the conversation that owns the message.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class, 'conversation_id');
    }

    /**
     * Get the sender of the message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Check if message is from bot.
     */
    public function isFromBot(): bool
    {
        return $this->sender_type === 'bot';
    }

    /**
     * Check if message is from user.
     */
    public function isFromUser(): bool
    {
        return $this->sender_type === 'user';
    }

    /**
     * Check if message is from agent.
     */
    public function isFromAgent(): bool
    {
        return $this->sender_type === 'agent';
    }
}
