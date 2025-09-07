<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyChain extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status',
        'chain_position',
        'total_chain_length',
        'estimated_completion_date',
        'actual_completion_date',
        'team_id',
        'lead_agent_id',
        'priority_level',
        'notes'
    ];

    protected $casts = [
        'estimated_completion_date' => 'date',
        'actual_completion_date' => 'date',
        'chain_position' => 'integer',
        'total_chain_length' => 'integer',
        'priority_level' => 'integer'
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function leadAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lead_agent_id');
    }

    public function chainLinks(): HasMany
    {
        return $this->hasMany(ChainLink::class);
    }

    public function getProgressPercentage(): float
    {
        $completedLinks = $this->chainLinks()->where('status', 'completed')->count();
        $totalLinks = $this->chainLinks()->count();

        return $totalLinks > 0 ? ($completedLinks / $totalLinks) * 100 : 0;
    }

    public function isComplete(): bool
    {
        return $this->status === 'completed';
    }

    public function getBottlenecks()
    {
        return $this->chainLinks()
            ->where('status', 'blocked')
            ->orWhere('status', 'delayed')
            ->get();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority_level', $priority);
    }
}