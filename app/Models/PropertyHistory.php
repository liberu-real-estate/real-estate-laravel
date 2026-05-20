<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'event_type',
        'description',
        'old_price',
        'new_price',
        'old_status',
        'new_status',
        'changes',
        'event_date',
        'user_id',
    ];

    protected $casts = [
        'event_date' => 'date',
        'old_price' => 'decimal:2',
        'new_price' => 'decimal:2',
        'changes' => 'array',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by event type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Scope to get price changes only
     */
    public function scopePriceChanges($query)
    {
        return $query->where('event_type', 'price_change');
    }

    /**
     * Scope to get sales history
     */
    public function scopeSales($query)
    {
        return $query->where('event_type', 'sale');
    }

    /**
     * Get the price change percentage if applicable
     */
    public function getPriceChangePercentage(): ?float
    {
        if ($this->old_price && $this->new_price && $this->old_price > 0) {
            return (($this->new_price - $this->old_price) / $this->old_price) * 100;
        }

        return null;
    }

    /**
     * Get formatted event description
     */
    public function getFormattedDescription(): string
    {
        return match($this->event_type) {
            'price_change' => sprintf(
                'Price changed from %s to %s (%s%.2f%%)',
                number_format($this->old_price, 2),
                number_format($this->new_price, 2),
                $this->getPriceChangePercentage() >= 0 ? '+' : '',
                $this->getPriceChangePercentage()
            ),
            'status_change' => sprintf(
                'Status changed from %s to %s',
                $this->old_status,
                $this->new_status
            ),
            'sale' => sprintf(
                'Property sold for %s',
                number_format($this->new_price, 2)
            ),
            default => $this->description,
        };
    }
}
