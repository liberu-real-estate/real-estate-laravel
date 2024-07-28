<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponentSettings extends Model
{
    use HasFactory;

    protected $fillable = ['component_name', 'is_enabled'];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];
}