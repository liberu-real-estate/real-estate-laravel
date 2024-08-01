<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentPerformanceMetrics extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'date',
        'sales_volume',
        'number_of_transactions',
        'customer_satisfaction_rating',
        'average_days_on_market',
        'lead_conversion_rate',
    ];

    protected $casts = [
        'date' => 'date',
        'sales_volume' => 'float',
        'number_of_transactions' => 'integer',
        'customer_satisfaction_rating' => 'float',
        'average_days_on_market' => 'float',
        'lead_conversion_rate' => 'float',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}