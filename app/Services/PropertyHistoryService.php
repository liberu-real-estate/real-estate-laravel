<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyHistory;
use Illuminate\Support\Facades\Auth;

class PropertyHistoryService
{
    /**
     * Record a property history event
     */
    public function recordEvent(
        Property $property,
        string $eventType,
        string $description,
        array $additionalData = []
    ): PropertyHistory {
        $data = array_merge([
            'property_id' => $property->id,
            'event_type' => $eventType,
            'description' => $description,
            'event_date' => now()->toDateString(),
            'user_id' => Auth::id(),
        ], $additionalData);

        return PropertyHistory::create($data);
    }

    /**
     * Track price change
     */
    public function trackPriceChange(Property $property, float $oldPrice, float $newPrice): PropertyHistory
    {
        $percentage = (($newPrice - $oldPrice) / $oldPrice) * 100;
        $direction = $percentage >= 0 ? 'increased' : 'decreased';
        
        return $this->recordEvent(
            $property,
            'price_change',
            sprintf(
                'Price %s from %s to %s (%.2f%%)',
                $direction,
                number_format($oldPrice, 2),
                number_format($newPrice, 2),
                abs($percentage)
            ),
            [
                'old_price' => $oldPrice,
                'new_price' => $newPrice,
                'changes' => [
                    'field' => 'price',
                    'old_value' => $oldPrice,
                    'new_value' => $newPrice,
                    'percentage_change' => $percentage,
                ]
            ]
        );
    }

    /**
     * Track status change
     */
    public function trackStatusChange(Property $property, string $oldStatus, string $newStatus): PropertyHistory
    {
        return $this->recordEvent(
            $property,
            'status_change',
            sprintf('Status changed from %s to %s', $oldStatus, $newStatus),
            [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changes' => [
                    'field' => 'status',
                    'old_value' => $oldStatus,
                    'new_value' => $newStatus,
                ]
            ]
        );
    }

    /**
     * Track property sale
     */
    public function trackSale(Property $property, float $salePrice, ?\DateTime $saleDate = null): PropertyHistory
    {
        return $this->recordEvent(
            $property,
            'sale',
            sprintf('Property sold for %s', number_format($salePrice, 2)),
            [
                'new_price' => $salePrice,
                'event_date' => $saleDate ? $saleDate->format('Y-m-d') : now()->toDateString(),
                'changes' => [
                    'field' => 'sold_date',
                    'sale_price' => $salePrice,
                ]
            ]
        );
    }

    /**
     * Track property listing
     */
    public function trackListing(Property $property, float $listingPrice): PropertyHistory
    {
        return $this->recordEvent(
            $property,
            'listing',
            sprintf('Property listed for %s', number_format($listingPrice, 2)),
            [
                'new_price' => $listingPrice,
                'changes' => [
                    'field' => 'list_date',
                    'listing_price' => $listingPrice,
                ]
            ]
        );
    }

    /**
     * Track general property update
     */
    public function trackUpdate(Property $property, array $changes, string $description = null): PropertyHistory
    {
        if (!$description) {
            $fields = array_keys($changes);
            $description = sprintf('Updated: %s', implode(', ', $fields));
        }

        return $this->recordEvent(
            $property,
            'update',
            $description,
            [
                'changes' => $changes
            ]
        );
    }

    /**
     * Get property history with filters
     */
    public function getHistory(
        Property $property,
        ?string $eventType = null,
        ?int $limit = null
    ) {
        $query = $property->histories();

        if ($eventType) {
            $query->where('event_type', $eventType);
        }

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get price history for a property
     */
    public function getPriceHistory(Property $property, ?int $limit = null)
    {
        return $this->getHistory($property, 'price_change', $limit);
    }

    /**
     * Get sales history for a property
     */
    public function getSalesHistory(Property $property, ?int $limit = null)
    {
        return $this->getHistory($property, 'sale', $limit);
    }

    /**
     * Check if property has been updated
     */
    public function hasPropertyChanged(Property $property, array $dirtyAttributes): bool
    {
        $trackedFields = ['price', 'status', 'sold_date', 'list_date'];
        
        return count(array_intersect(array_keys($dirtyAttributes), $trackedFields)) > 0;
    }

    /**
     * Auto-track property changes based on dirty attributes
     */
    public function autoTrackChanges(Property $property, array $original): void
    {
        $dirty = $property->getDirty();

        // Track price changes
        if (isset($dirty['price']) && isset($original['price'])) {
            $this->trackPriceChange($property, $original['price'], $dirty['price']);
        }

        // Track status changes
        if (isset($dirty['status']) && isset($original['status'])) {
            $this->trackStatusChange($property, $original['status'], $dirty['status']);
        }

        // Track when property is sold
        if (isset($dirty['sold_date']) && !isset($original['sold_date']) && $dirty['sold_date']) {
            $this->trackSale($property, $property->price, new \DateTime($dirty['sold_date']));
        }

        // Track when property is listed
        if (isset($dirty['list_date']) && !isset($original['list_date']) && $dirty['list_date']) {
            $this->trackListing($property, $property->price);
        }
    }
}
