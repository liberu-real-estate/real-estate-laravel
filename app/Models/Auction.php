<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Broadcast;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'start_time',
        'end_time',
        'starting_price',
        'current_bid',
        'minimum_increment',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    protected static function booted()
    {
        static::updated(function ($auction) {
            Broadcast::channel('auction.' . $auction->id, function ($user) use ($auction) {
                return true;
            });
        });
    }
}