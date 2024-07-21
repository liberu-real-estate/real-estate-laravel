<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    protected $fillable = [
        'name',
        'currency',
        'default_language',
        'address',
        'country',
        'email',
    ];
}