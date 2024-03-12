<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model representing a key location entity.
 * Used to interact with the 'key_locations' database table.
 */
class KeyLocation extends Model
{
    protected $fillable = ['location_name', 'address'];
}
