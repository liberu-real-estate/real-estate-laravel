<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Property extends Model
{
    protected $fillable = [
        'title',
        'description',
        'location',
        'price',
        'bedrooms',
        'bathrooms',
        'area_sqft',
        'year_built',
        'property_type',
        'status',
        'list_date',
        'sold_date',
        'user_id',
        'agent_id',
        'virtual_tour_url',
        'is_featured',
        'zoopla_id',
        'onthemarket_id',
        'last_synced_at',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
    ];

    // Relationships
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'property_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'property_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'property_id');
    }

    public function features()
    {
        return $this->hasMany(PropertyFeature::class, 'property_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    // Scopes
    public function scopeSearch(Builder $query, $search): Builder
    {
        return $query->where(function ($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%');
        });
    }

    public function scopePriceRange(Builder $query, $min, $max): Builder
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeBedrooms(Builder $query, $min, $max): Builder
    {
        return $query->whereBetween('bedrooms', [$min, $max]);
    }

    public function scopeBathrooms(Builder $query, $min, $max): Builder
    {
        return $query->whereBetween('bathrooms', [$min, $max]);
    }

    public function scopeAreaRange(Builder $query, $min, $max): Builder
    {
        return $query->whereBetween('area_sqft', [$min, $max]);
    }

    public function scopePropertyType(Builder $query, $type): Builder
    {
        return $query->where('property_type', $type);
    }

    public function scopeHasAmenities(Builder $query, array $amenities): Builder
    {
        return $query->whereHas('features', function ($query) use ($amenities) {
            $query->whereIn('feature_name', $amenities);
        }, '=', count($amenities));
    }

    public function scopeNeedsSyncing(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->whereNull('last_synced_at')
                  ->orWhere('updated_at', '>', 'last_synced_at');
        });
    }
}
