<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $primaryKey = 'property_id';

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
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'property_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'property_id');
    }
    /**
     * Scope a query to only include properties that match the search criteria.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%');
        });
    }
  

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
}

