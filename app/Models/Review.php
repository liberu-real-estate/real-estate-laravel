<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'review_id';

    protected $fillable = [
        'user_id',
        'property_id',
        'rating',
        'comment',
        'review_date',
    ];

    protected $casts = [
        'review_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function scopeHighRated($query)
    {
        return $query->where('rating', '>=', 4);
    }
}

