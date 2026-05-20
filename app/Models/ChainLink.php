<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChainLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_chain_id',
        'property_id',
        'buyer_id',
        'seller_id',
        'position_in_chain',
        'link_type',
        'status',
        'estimated_completion',
        'actual_completion',
        'sale_price',
        'mortgage_approved',
        'survey_completed',
        'legal_work_status',
        'exchange_date',
        'completion_date',
        'notes',
        'blocking_issues'
    ];

    protected $casts = [
        'estimated_completion' => 'date',
        'actual_completion' => 'date',
        'exchange_date' => 'date',
        'completion_date' => 'date',
        'sale_price' => 'decimal:2',
        'mortgage_approved' => 'boolean',
        'survey_completed' => 'boolean',
        'blocking_issues' => 'array'
    ];

    public function propertyChain(): BelongsTo
    {
        return $this->belongsTo(PropertyChain::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function isReadyForExchange(): bool
    {
        return $this->mortgage_approved && 
               $this->survey_completed && 
               $this->legal_work_status === 'completed';
    }

    public function getDelayReason(): ?string
    {
        if (!$this->mortgage_approved) {
            return 'Mortgage not approved';
        }
        if (!$this->survey_completed) {
            return 'Survey not completed';
        }
        if ($this->legal_work_status !== 'completed') {
            return 'Legal work pending';
        }
        return null;
    }

    public function scopeBlocked($query)
    {
        return $query->where('status', 'blocked');
    }

    public function scopeReadyForExchange($query)
    {
        return $query->where('mortgage_approved', true)
                    ->where('survey_completed', true)
                    ->where('legal_work_status', 'completed');
    }
}