<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Booking extends Model
{
    protected $fillable = [
        'date',
        'time',
        'staff_id',
        'user_id',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
/**
 * Represents a booking entity in the database.
 */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
