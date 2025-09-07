<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorQuote extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'property_id',
        'work_description',
        'quote_amount',
        'labor_cost',
        'materials_cost',
        'additional_costs',
        'quote_date',
        'valid_until',
        'estimated_duration',
        'start_date',
        'completion_date',
        'terms_conditions',
        'status',
        'notes',
        'requested_by',
        'approved_by',
        'rejection_reason'
    ];

    protected $casts = [
        'quote_date' => 'date',
        'valid_until' => 'date',
        'start_date' => 'date',
        'completion_date' => 'date',
        'quote_amount' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'materials_cost' => 'decimal:2',
        'additional_costs' => 'decimal:2',
        'estimated_duration' => 'integer'
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isValid(): bool
    {
        return $this->valid_until >= now();
    }

    public function isExpired(): bool
    {
        return $this->valid_until < now();
    }

    public function getTotalCost(): float
    {
        return $this->labor_cost + $this->materials_cost + $this->additional_costs;
    }

    public function scopeValid($query)
    {
        return $query->where('valid_until', '>=', now());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}