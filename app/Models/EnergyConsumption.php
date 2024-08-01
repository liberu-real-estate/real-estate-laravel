<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'consumption_date' => 'date',
        'electricity_usage' => 'float',
        'gas_usage' => 'float',
        'water_usage' => 'float',
        'total_cost' => 'decimal:2',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}