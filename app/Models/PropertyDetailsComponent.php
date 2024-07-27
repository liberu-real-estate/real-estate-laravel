<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyDetailsComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'component_name',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
    ];
}
