<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    protected $primaryKey = 'favorite_id';

    protected $fillable = [
        'user_id',
        'property_id',
        'team_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}

