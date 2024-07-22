<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'message',
        'status',
        'team_id',
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}