<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $table = 'tenants';

    protected $fillable = [
        'name', 'first_name', 'last_name', 'email', 'password', 'phone', 'team_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getFullNameAttribute(): string
    {
        if ($this->first_name && $this->last_name) {
            return $this->first_name . ' ' . $this->last_name;
        }
        return $this->name ?? '';
    }

    public function leases()
    {
        return $this->hasMany(Lease::class, 'tenant_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'tenant_id');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
