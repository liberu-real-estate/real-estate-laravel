<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Neighborhood extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'schools',
        'amenities',
        'crime_rate',
        'median_income',
        'population',
        'walk_score',
        'transit_score',
        'last_updated',
    ];

    protected $casts = [
        'schools' => 'array',
        'amenities' => 'array',
        'last_updated' => 'datetime',
    ];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Get the average rating for this neighborhood.
     * Note: For better performance when displaying multiple neighborhoods,
     * use eager loading: Neighborhood::withAvg('reviews', 'rating')
     * 
     * @return float
     */
    public function averageRating()
    {
        return $this->reviews()->where('approved', true)->avg('rating') ?? 0;
    }

    /**
     * Get the count of approved reviews for this neighborhood.
     * Note: For better performance when displaying multiple neighborhoods,
     * use eager loading: Neighborhood::withCount('reviews')
     * 
     * @return int
     */
    public function reviewCount()
    {
        return $this->reviews()->where('approved', true)->count();
    }
}