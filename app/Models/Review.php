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
        'reviewable_id',
        'reviewable_type',
        'rating',
        'comment',
        'review_date',
        'approved',
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
}

