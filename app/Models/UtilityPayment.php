<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilityPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'energy_consumption_id',
        'amount',
        'payment_date',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function energyConsumption()
    {
        return $this->belongsTo(EnergyConsumption::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}