<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Property;

class Booking extends Model
{
    protected $fillable = [
        'date',
        'time',
        'staff_id',
        'user_id',
        'notes',
        'property_id',
        'name',
        'contact',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function setStaffIdAttribute($value)
    {
        $this->attributes['staff_id'] = $value ?? $this->getDefaultStaffId();
    }

    private function getDefaultStaffId()
    {
        return User::where('role', 'staff')->first()->id ?? null;
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
