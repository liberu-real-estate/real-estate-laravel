<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_type',
        'min_price',
        'max_price',
        'min_bedrooms',
        'max_bedrooms',
        'location',
        'notification_frequency',
    ];

    protected $casts = [
        'min_price' => 'float',
        'max_price' => 'float',
        'min_bedrooms' => 'integer',
        'max_bedrooms' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}