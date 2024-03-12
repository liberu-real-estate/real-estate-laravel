<?php

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
