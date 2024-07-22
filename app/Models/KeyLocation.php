<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeyLocation extends Model
{
    protected $fillable = ['location_name', 'address', 'team_id'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
