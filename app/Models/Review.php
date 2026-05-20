<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'reviewable_id',
        'reviewable_type',
        'rating',
        'comment',
        'title',
        'review_date',
        'approved',
        'moderation_status',
        'ip_address',
        'helpful_votes',
        'unhelpful_votes',
    ];

    protected $casts = [
        'review_date' => 'datetime',
        'approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewable()
    {
        return $this->morphTo();
    }

    public function scopeHighRated($query)
    {
        return $query->where('rating', '>=', 4);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeForTenants($query)
    {
        return $query->where('reviewable_type', Tenant::class);
    }

    public function scopeForLandlords($query)
    {
        return $query->where('reviewable_type', User::class)->whereHas('reviewable', function ($query) {
            $query->whereHas('roles', function ($query) {
                $query->where('name', 'landlord');
            });
        });
    }

    public function scopeForNeighborhoods($query)
    {
        return $query->where('reviewable_type', Neighborhood::class);
    }

    public function scopeForProperties($query)
    {
        return $query->where('reviewable_type', Property::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('moderation_status', 'pending');
    }

    public function isAuthentic()
    {
        // Implement logic to check if the review is authentic
        // For example, check if the user has a verified account, has made multiple reviews, etc.
        return $this->user->hasVerifiedEmail() && $this->user->reviews()->count() > 1;
    }

    public function markAsHelpful()
    {
        $this->increment('helpful_votes');
    }

    public function markAsUnhelpful()
    {
        $this->increment('unhelpful_votes');
    }
}

