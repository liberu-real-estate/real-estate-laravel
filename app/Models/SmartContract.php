<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmartContract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contract_address',
        'contract_type',
        'lease_agreement_id',
        'property_id',
        'landlord_id',
        'tenant_id',
        'team_id',
        'rent_amount',
        'security_deposit',
        'lease_start_date',
        'lease_end_date',
        'status',
        'landlord_signed',
        'tenant_signed',
        'deployed_at',
        'activated_at',
        'terminated_at',
        'blockchain_network',
        'transaction_hash',
        'agreement_hash',
        'abi',
        'bytecode',
        'total_rent_paid',
        'last_rent_payment',
        'rent_payments_count',
    ];

    protected $casts = [
        'rent_amount' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'total_rent_paid' => 'decimal:2',
        'lease_start_date' => 'datetime',
        'lease_end_date' => 'datetime',
        'deployed_at' => 'datetime',
        'activated_at' => 'datetime',
        'terminated_at' => 'datetime',
        'last_rent_payment' => 'datetime',
        'landlord_signed' => 'boolean',
        'tenant_signed' => 'boolean',
        'abi' => 'array',
        'rent_payments_count' => 'integer',
    ];

    // Relationships
    public function leaseAgreement()
    {
        return $this->belongsTo(LeaseAgreement::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function transactions()
    {
        return $this->hasMany(SmartContractTransaction::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFullySigned($query)
    {
        return $query->where('landlord_signed', true)
                     ->where('tenant_signed', true);
    }

    // Helper methods
    public function isFullySigned(): bool
    {
        return $this->landlord_signed && $this->tenant_signed;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isTerminated(): bool
    {
        return $this->status === 'terminated';
    }

    public function isExpired(): bool
    {
        return $this->lease_end_date < now();
    }

    public function getRemainingDays(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        return now()->diffInDays($this->lease_end_date);
    }

    public function getMonthlyRentDue(): float
    {
        return (float) $this->rent_amount;
    }

    public function getTotalRentExpected(): float
    {
        $months = $this->lease_start_date->diffInMonths($this->lease_end_date);
        return $months * (float) $this->rent_amount;
    }

    public function getRentPaymentProgress(): float
    {
        $expected = $this->getTotalRentExpected();
        if ($expected == 0) {
            return 0;
        }
        return ((float) $this->total_rent_paid / $expected) * 100;
    }

    public function canActivate(): bool
    {
        return $this->isPending() && $this->isFullySigned() && $this->deployed_at !== null;
    }

    public function activate(): bool
    {
        if (!$this->canActivate()) {
            return false;
        }

        $this->update([
            'status' => 'active',
            'activated_at' => now(),
        ]);

        return true;
    }

    public function terminate(): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        $this->update([
            'status' => 'terminated',
            'terminated_at' => now(),
        ]);

        return true;
    }

    public function recordRentPayment(float $amount): void
    {
        $this->increment('rent_payments_count');
        $this->increment('total_rent_paid', $amount);
        $this->update(['last_rent_payment' => now()]);
    }
}
