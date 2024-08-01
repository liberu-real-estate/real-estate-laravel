<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'license_number',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function performanceMetrics(): HasMany
    {
        return $this->hasMany(AgentPerformanceMetrics::class);
    }

    public function averageRating(): float
    {
        return $this->performanceMetrics()->avg('customer_satisfaction_rating') ?? 0;
    }

    public function totalSales(): float
    {
        return $this->performanceMetrics()->sum('sales_volume') ?? 0;
    }
}