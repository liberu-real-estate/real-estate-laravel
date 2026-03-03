<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesProgression extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'transaction_id',
        'agent_id',
        'team_id',
        'stage',
        'sale_price',
        'offer_accepted_date',
        'exchange_date',
        'completion_date',
        'buyer_solicitor_name',
        'buyer_solicitor_email',
        'buyer_solicitor_phone',
        'seller_solicitor_name',
        'seller_solicitor_email',
        'seller_solicitor_phone',
        'mortgage_lender',
        'mortgage_broker',
        'checklist_items',
        'notes',
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
        'offer_accepted_date' => 'date',
        'exchange_date' => 'date',
        'completion_date' => 'date',
        'checklist_items' => 'array',
    ];

    public const STAGES = [
        'offer_accepted' => 'Offer Accepted',
        'solicitors_instructed' => 'Solicitors Instructed',
        'searches_ordered' => 'Searches Ordered',
        'searches_received' => 'Searches Received',
        'enquiries_raised' => 'Enquiries Raised',
        'enquiries_answered' => 'Enquiries Answered',
        'mortgage_offer_received' => 'Mortgage Offer Received',
        'exchange_ready' => 'Ready to Exchange',
        'exchanged' => 'Exchanged',
        'completion_date_set' => 'Completion Date Set',
        'completed' => 'Completed',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function getStageLabelAttribute(): string
    {
        return self::STAGES[$this->stage] ?? ucfirst(str_replace('_', ' ', $this->stage));
    }

    public function getStageProgressPercentage(): int
    {
        $stages = array_keys(self::STAGES);
        $currentIndex = array_search($this->stage, $stages);

        if ($currentIndex === false) {
            return 0;
        }

        return (int) round((($currentIndex + 1) / count($stages)) * 100);
    }

    public function isCompleted(): bool
    {
        return $this->stage === 'completed';
    }

    public function scopeActive($query)
    {
        return $query->where('stage', '!=', 'completed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('stage', 'completed');
    }

    public function scopeByStage($query, string $stage)
    {
        return $query->where('stage', $stage);
    }
}
