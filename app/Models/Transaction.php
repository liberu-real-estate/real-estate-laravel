<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'property_id',
        'buyer_id',
        'seller_id',
        'transaction_date',
        'transaction_amount',
        'status',
        'commission_amount',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'transaction_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function buyer()
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

    public function calculateCommission()
    {
        // Example commission calculation (3% of transaction amount)
        $this->commission_amount = $this->transaction_amount * 0.03;
        $this->save();
    }

    public function generateContractualDocument()
    {
        // Implement document generation logic here
        // This is a placeholder for the actual implementation
        return "Contract for Transaction {$this->transaction_id}";
    }
}

