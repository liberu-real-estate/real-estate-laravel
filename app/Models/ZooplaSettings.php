<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZooplaSettings extends Model
{
    protected $fillable = [
        'api_key',
        'base_uri',
        'sync_frequency',
    ];
}