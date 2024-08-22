<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    // protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'property_id',
        'buyer_id',
        'seller_id',
        'date',
        'amount',
        'status',
        'commission_amount',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function calculateCommission()
    {
        // Implement commission calculation logic here
        $commissionRate = 0.03; // 3% commission rate
        $this->commission_amount = $this->transaction_amount * $commissionRate;
        $this->save();
    }

    public function scopeCompleted(Builder $query) : void
    {
        $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopePending(Builder $query) : void
    {
        $query->where('status', self::STATUS_PENDING);
    }
}

