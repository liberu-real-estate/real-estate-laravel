<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RightMoveSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_key',
        'base_uri',
        'sync_frequency',
    ];
}