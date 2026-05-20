<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'work_order_id',
        'reviewer_id',
        'rating',
        'quality_rating',
        'timeliness_rating',
        'communication_rating',
        'professionalism_rating',
        'value_rating',
        'review_text',
        'pros',
        'cons',
        'would_recommend',
        'would_hire_again',
        'review_date',
        'is_verified',
        'helpful_votes'
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'quality_rating' => 'integer',
        'timeliness_rating' => 'integer',
        'communication_rating' => 'integer',
        'professionalism_rating' => 'integer',
        'value_rating' => 'integer',
        'would_recommend' => 'boolean',
        'would_hire_again' => 'boolean',
        'review_date' => 'date',
        'is_verified' => 'boolean',
        'helpful_votes' => 'integer',
        'pros' => 'array',
        'cons' => 'array'
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function getOverallRating(): float
    {
        $ratings = [
            $this->quality_rating,
            $this->timeliness_rating,
            $this->communication_rating,
            $this->professionalism_rating,
            $this->value_rating
        ];

        $validRatings = array_filter($ratings, fn($rating) => $rating > 0);

        return count($validRatings) > 0 ? array_sum($validRatings) / count($validRatings) : 0;
    }

    public function isPositive(): bool
    {
        return $this->rating >= 4.0;
    }

    public function isNegative(): bool
    {
        return $this->rating <= 2.0;
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopePositive($query)
    {
        return $query->where('rating', '>=', 4.0);
    }

    public function scopeNegative($query)
    {
        return $query->where('rating', '<=', 2.0);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('review_date', '>=', now()->subDays($days));
    }
}