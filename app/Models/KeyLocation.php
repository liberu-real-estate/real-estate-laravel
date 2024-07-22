<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeyLocation extends Model
{
    protected $fillable = ['location_name', 'address', 'team_id'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
