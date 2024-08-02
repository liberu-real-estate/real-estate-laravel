<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnergyConsumption extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'consumption_date',
        'electricity_usage',
        'gas_usage',
        'water_usage',
        'total_cost',
        'status',
        'due_date',
    ];
    
    protected $casts = [
        'consumption_date' => 'date',
        'electricity_usage' => 'float',
        'gas_usage' => 'float',
        'water_usage' => 'float',
        'total_cost' => 'decimal:2',
        'due_date' => 'date',
    ];
    
    public function utilityPayments()
    {
        return $this->hasMany(UtilityPayment::class);
    }
    
    public function getRemainingBalanceAttribute()
    {
        return $this->total_cost - $this->utilityPayments()->sum('amount');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}