<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ViewingFeedback extends Model
{
    use HasFactory;

    protected $table = 'viewing_feedbacks';

    protected $fillable = [
        'appointment_id',
        'property_id',
        'viewer_id',
        'viewer_name',
        'viewer_email',
        'overall_rating',
        'price_rating',
        'condition_rating',
        'location_rating',
        'size_rating',
        'positive_comments',
        'negative_comments',
        'general_comments',
        'interest_level',
        'would_make_offer',
        'offer_price',
        'token',
        'feedback_requested_at',
        'feedback_submitted_at',
        'team_id',
    ];

    protected $casts = [
        'overall_rating' => 'integer',
        'price_rating' => 'integer',
        'condition_rating' => 'integer',
        'location_rating' => 'integer',
        'size_rating' => 'integer',
        'would_make_offer' => 'boolean',
        'offer_price' => 'decimal:2',
        'feedback_requested_at' => 'datetime',
        'feedback_submitted_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    public function viewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'viewer_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function hasBeenSubmitted(): bool
    {
        return $this->feedback_submitted_at !== null;
    }

    public function getAverageRating(): ?float
    {
        $ratings = array_filter([
            $this->overall_rating,
            $this->price_rating,
            $this->condition_rating,
            $this->location_rating,
            $this->size_rating,
        ]);

        if (empty($ratings)) {
            return null;
        }

        return round(array_sum($ratings) / count($ratings), 1);
    }

    public function getInterestLevelLabel(): string
    {
        return match ($this->interest_level) {
            'very_interested' => 'Very Interested',
            'interested' => 'Interested',
            'neutral' => 'Neutral',
            'not_interested' => 'Not Interested',
            'definitely_not' => 'Definitely Not',
            default => 'Unknown',
        };
    }

    public function scopeSubmitted($query)
    {
        return $query->whereNotNull('feedback_submitted_at');
    }

    public function scopePending($query)
    {
        return $query->whereNull('feedback_submitted_at');
    }

    public function scopeInterestedViewers($query)
    {
        return $query->whereIn('interest_level', ['very_interested', 'interested']);
    }

    protected static function booted(): void
    {
        static::creating(function (ViewingFeedback $feedback) {
            if (empty($feedback->token)) {
                $feedback->token = Str::random(32);
            }
        });
    }
}
