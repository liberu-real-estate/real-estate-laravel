<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'amount',
        'payment_date',
        'status',
        'payment_method',
        'tenant_id',
        'invoice_id',
        'sage_id',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (!$payment->tenant_id && auth()->check()) {
                $payment->tenant_id = auth()->id();
            }
        });
    }

    public function scopeForTenant($query)
    {
        return $query->where('tenant_id', auth()->id());
    }
}