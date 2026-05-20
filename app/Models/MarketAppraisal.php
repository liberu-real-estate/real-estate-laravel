<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketAppraisal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'appraisal_type',
        'current_market_value',
        'rental_value_monthly',
        'rental_value_weekly',
        'price_per_sqft',
        'market_trend',
        'demand_level',
        'supply_level',
        'days_on_market_average',
        'comparable_sales',
        'market_factors',
        'location_score',
        'condition_score',
        'features_score',
        'overall_score',
        'appraisal_date',
        'valid_until',
        'appraiser_id',
        'methodology',
        'confidence_level',
        'market_segment',
        'target_buyer_profile',
        'marketing_recommendations',
        'pricing_strategy',
        'team_id',
        'notes'
    ];

    protected $casts = [
        'appraisal_date' => 'date',
        'valid_until' => 'date',
        'current_market_value' => 'decimal:2',
        'rental_value_monthly' => 'decimal:2',
        'rental_value_weekly' => 'decimal:2',
        'price_per_sqft' => 'decimal:2',
        'location_score' => 'integer',
        'condition_score' => 'integer',
        'features_score' => 'integer',
        'overall_score' => 'integer',
        'confidence_level' => 'integer',
        'comparable_sales' => 'array',
        'market_factors' => 'array',
        'marketing_recommendations' => 'array',
        'target_buyer_profile' => 'array'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function appraiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'appraiser_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function isValid(): bool
    {
        return $this->valid_until >= now();
    }

    public function getMarketPosition(): string
    {
        if ($this->overall_score >= 85) {
            return 'Premium Market';
        } elseif ($this->overall_score >= 70) {
            return 'Strong Market';
        } elseif ($this->overall_score >= 55) {
            return 'Average Market';
        } elseif ($this->overall_score >= 40) {
            return 'Weak Market';
        } else {
            return 'Poor Market';
        }
    }

    public function getYieldEstimate(): ?float
    {
        if ($this->current_market_value && $this->rental_value_monthly) {
            return ($this->rental_value_monthly * 12 / $this->current_market_value) * 100;
        }
        return null;
    }

    public function getConfidenceText(): string
    {
        return match(true) {
            $this->confidence_level >= 90 => 'Very High',
            $this->confidence_level >= 75 => 'High',
            $this->confidence_level >= 60 => 'Medium',
            $this->confidence_level >= 45 => 'Low',
            default => 'Very Low'
        };
    }

    public function getDemandIndicator(): string
    {
        return match($this->demand_level) {
            'very_high' => 'Very High Demand',
            'high' => 'High Demand',
            'medium' => 'Medium Demand',
            'low' => 'Low Demand',
            'very_low' => 'Very Low Demand',
            default => 'Unknown'
        };
    }

    public function scopeValid($query)
    {
        return $query->where('valid_until', '>=', now());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('appraisal_type', $type);
    }

    public function scopeHighConfidence($query)
    {
        return $query->where('confidence_level', '>=', 75);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('appraisal_date', '>=', now()->subDays($days));
    }
}