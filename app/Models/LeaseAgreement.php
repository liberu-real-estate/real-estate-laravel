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
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_signed' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = Crypt::encryptString($value);
    }

    public function getContentAttribute($value)
    {
        return Crypt::decryptString($value);
    }
}