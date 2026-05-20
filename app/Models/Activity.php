<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'user_id',
        'type',
        'description',
        'scheduled_at',
        'completed_at',
        'team_id',
        'property_id',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public static function trackPropertyView($userId, $propertyId)
    {
        return self::create([
            'user_id' => $userId,
            'property_id' => $propertyId,
            'type' => 'property_view',
            'description' => 'Viewed property',
        ]);
    }

}
