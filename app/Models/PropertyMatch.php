<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'property_id',
        'match_score',
        'match_criteria',
        'price_match',
        'location_match',
        'size_match',
        'features_match',
        'type_match',
        'status',
        'viewed_by_buyer',
        'buyer_interest_level',
        'agent_notes',
        'match_date',
        'last_updated',
        'auto_generated',
        'team_id'
    ];

    protected $casts = [
        'match_score' => 'decimal:2',
        'price_match' => 'decimal:2',
        'location_match' => 'decimal:2',
        'size_match' => 'decimal:2',
        'features_match' => 'decimal:2',
        'type_match' => 'decimal:2',
        'viewed_by_buyer' => 'boolean',
        'auto_generated' => 'boolean',
        'match_date' => 'datetime',
        'last_updated' => 'datetime',
        'match_criteria' => 'array',
        'buyer_interest_level' => 'integer'
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function getMatchGrade(): string
    {
        if ($this->match_score >= 90) {
            return 'Excellent';
        } elseif ($this->match_score >= 80) {
            return 'Very Good';
        } elseif ($this->match_score >= 70) {
            return 'Good';
        } elseif ($this->match_score >= 60) {
            return 'Fair';
        } else {
            return 'Poor';
        }
    }

    public function isHighMatch(): bool
    {
        return $this->match_score >= 80;
    }

    public function getWeakestCriteria(): string
    {
        $criteria = [
            'price' => $this->price_match,
            'location' => $this->location_match,
            'size' => $this->size_match,
            'features' => $this->features_match,
            'type' => $this->type_match
        ];

        return array_search(min($criteria), $criteria);
    }

    public function getStrongestCriteria(): string
    {
        $criteria = [
            'price' => $this->price_match,
            'location' => $this->location_match,
            'size' => $this->size_match,
            'features' => $this->features_match,
            'type' => $this->type_match
        ];

        return array_search(max($criteria), $criteria);
    }

    public function getBuyerInterestText(): string
    {
        return match($this->buyer_interest_level) {
            1 => 'Not Interested',
            2 => 'Low Interest',
            3 => 'Moderate Interest',
            4 => 'High Interest',
            5 => 'Very Interested',
            default => 'Unknown'
        };
    }

    public function markAsViewed(): void
    {
        $this->update([
            'viewed_by_buyer' => true,
            'last_updated' => now()
        ]);
    }

    public function updateInterestLevel(int $level): void
    {
        $this->update([
            'buyer_interest_level' => $level,
            'last_updated' => now()
        ]);
    }

    public function scopeHighMatch($query)
    {
        return $query->where('match_score', '>=', 80);
    }

    public function scopeUnviewed($query)
    {
        return $query->where('viewed_by_buyer', false);
    }

    public function scopeInterested($query)
    {
        return $query->where('buyer_interest_level', '>=', 3);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('match_date', '>=', now()->subDays($days));
    }
}