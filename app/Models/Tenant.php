<?php

/**
 * Represents the Tenant entity in the database, including its relationships and properties.
 * This model is responsible for handling the data of tenants.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $table = 'tenants';

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Define relationships here if necessary
    // Example: 
    // public function leases() {
    //     return $this->hasMany(Lease::class);
    // }
}
