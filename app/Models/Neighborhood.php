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
    ];

    protected $casts = [
        'schools' => 'array',
        'amenities' => 'array',
    ];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}