<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone_number',
        // Add other relevant details here
    ];

    // Define relationships and business logic here if necessary
}
