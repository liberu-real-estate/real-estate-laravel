<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'status',
        'assigned_agent_id',
        'escalated_at',
    ];

    protected $casts = [
        'escalated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the conversation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the assigned agent for the conversation.
     */
    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    /**
     * Get the messages for the conversation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }

    /**
     * Check if conversation is escalated.
     */
    public function isEscalated(): bool
    {
        return $this->status === 'escalated';
    }

    /**
     * Escalate conversation to a live agent.
     */
    public function escalate(?int $agentId = null): void
    {
        $this->update([
            'status' => 'escalated',
            'assigned_agent_id' => $agentId,
            'escalated_at' => now(),
        ]);
    }
}
