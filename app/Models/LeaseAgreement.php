<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class LeaseAgreement extends Model
{
    protected $fillable = [
        'tenant_id',
        'property_id',
        'start_date',
        'end_date',
        'monthly_rent',
        'terms',
        'content',
        'is_signed',
        'smart_contract_address',
        'contract_status',
        'landlord_signed',
        'tenant_signed',
        'contract_deployed_at',
        'agreement_hash',
        'blockchain_network',
        'security_deposit',
        'team_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_signed' => 'boolean',
        'landlord_signed' => 'boolean',
        'tenant_signed' => 'boolean',
        'contract_deployed_at' => 'datetime',
        'monthly_rent' => 'decimal:2',
        'security_deposit' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function smartContract()
    {
        return $this->hasOne(SmartContract::class);
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = Crypt::encryptString($value);
    }

    public function getContentAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    // Helper methods for smart contract integration
    public function hasSmartContract(): bool
    {
        return !empty($this->smart_contract_address);
    }

    public function isFullySigned(): bool
    {
        return $this->landlord_signed && $this->tenant_signed;
    }

    public function canDeploySmartContract(): bool
    {
        return !$this->hasSmartContract() && 
               $this->start_date && 
               $this->end_date && 
               $this->monthly_rent > 0;
    }
}