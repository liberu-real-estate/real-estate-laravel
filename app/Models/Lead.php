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
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }
}