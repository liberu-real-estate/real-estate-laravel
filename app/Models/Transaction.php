<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'property_id',
        'buyer_id',
        'seller_id',
        'transaction_date',
        'transaction_amount',
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
}

