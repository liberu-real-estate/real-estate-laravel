<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CommunityEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'end_date',
        'location',
        'latitude',
        'longitude',
        'category',
        'organizer',
        'contact_email',
        'contact_phone',
        'website_url',
        'is_public',
        'property_id',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'end_date' => 'datetime',
        'is_public' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Get the property associated with this event.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Scope to get upcoming events.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc');
    }

    /**
     * Scope to get public events.
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope to get events by category.
     */
    public function scopeCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to get events near a location.
     */
    public function scopeNearby(Builder $query, float $latitude, float $longitude, float $radius = 10): Builder
    {
        return $query->selectRaw('*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance', [$latitude, $longitude, $latitude])
            ->having('distance', '<=', $radius)
            ->orderBy('distance');
    }

    /**
     * Get events for a specific month.
     */
    public function scopeForMonth(Builder $query, int $year, int $month): Builder
    {
        $startDate = now()->setYear($year)->setMonth($month)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        return $query->whereBetween('event_date', [$startDate, $endDate]);
    }
}
