<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BooминSettings extends Model
{
    protected $fillable = [
        'api_key',
        'base_uri',
        'sync_frequency',
    ];

    protected $casts = [
        'sync_frequency' => 'integer',
    ];
}