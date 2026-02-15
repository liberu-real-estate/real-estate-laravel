<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agent_id',
        'team_id',
        'match_score',
        'expertise_score',
        'performance_score',
        'availability_score',
        'location_score',
        'specialization_score',
        'match_reasons',
        'auto_generated',
        'status',
    ];

    protected $casts = [
        'match_score' => 'float',
        'expertise_score' => 'float',
        'performance_score' => 'float',
        'availability_score' => 'float',
        'location_score' => 'float',
        'specialization_score' => 'float',
        'match_reasons' => 'array',
        'auto_generated' => 'boolean',
    ];

    /**
     * Get the user that owns the match
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the agent for this match
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the team that owns this match
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Scope to get only pending matches
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get only accepted matches
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Mark match as accepted
     */
    public function accept(): bool
    {
        $this->status = 'accepted';
        return $this->save();
    }

    /**
     * Mark match as rejected
     */
    public function reject(): bool
    {
        $this->status = 'rejected';
        return $this->save();
    }
}
