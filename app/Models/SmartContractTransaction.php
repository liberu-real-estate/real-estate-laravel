<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartContractTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'smart_contract_id',
        'transaction_type',
        'transaction_hash',
        'initiated_by',
        'amount',
        'description',
        'metadata',
        'status',
        'confirmed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'confirmed_at' => 'datetime',
    ];

    // Relationships
    public function smartContract()
    {
        return $this->belongsTo(SmartContract::class);
    }

    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('transaction_type', $type);
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function confirm(): bool
    {
        return $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    public function fail(): bool
    {
        return $this->update([
            'status' => 'failed',
        ]);
    }
}
