<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyValuation;
use Illuminate\Support\Collection;

class PropertyValuationService
{
    public function createValuation(Property $property, array $data): PropertyValuation
    {
        // Mark previous valuations of same type as superseded
        $property->valuations()
            ->where('valuation_type', $data['valuation_type'])
            ->where('status', 'active')
            ->update(['status' => 'superseded']);

        return $property->valuations()->create($data);
    }

    public function getComparableProperties(Property $property, int $limit = 5): Collection
    {
        return Property::where('id', '!=', $property->id)
            ->where('property_type', $property->property_type)
            ->whereBetween('bedrooms', [$property->bedrooms - 1, $property->bedrooms + 1])
            ->whereBetween('bathrooms', [$property->bathrooms - 1, $property->bathrooms + 1])
            ->whereBetween('area_sqft', [$property->area_sqft * 0.8, $property->area_sqft * 1.2])
            ->whereHas('transactions', function ($query) {
                $query->where('transaction_type', 'sale')
                    ->where('completed_at', '>=', now()->subMonths(12));
            })
            ->with(['transactions' => function ($query) {
                $query->where('transaction_type', 'sale')
                    ->latest('completed_at')
                    ->limit(1);
            }])
            ->limit($limit)
            ->get();
    }

    public function calculateAutomaticValuation(Property $property): array
    {
        $comparables = $this->getComparableProperties($property);

        if ($comparables->isEmpty()) {
            return [
                'estimated_value' => null,
                'confidence_level' => 0,
                'method' => 'insufficient_data'
            ];
        }

        $totalValue = 0;
        $count = 0;
        $pricePerSqft = [];

        foreach ($comparables as $comparable) {
            $lastSale = $comparable->transactions->first();
            if ($lastSale) {
                $totalValue += $lastSale->sale_price;
                $count++;

                if ($comparable->area_sqft > 0) {
                    $pricePerSqft[] = $lastSale->sale_price / $comparable->area_sqft;
                }
            }
        }

        $averageValue = $count > 0 ? $totalValue / $count : 0;
        $averagePricePerSqft = count($pricePerSqft) > 0 ? array_sum($pricePerSqft) / count($pricePerSqft) : 0;

        $estimatedValue = $property->area_sqft > 0 && $averagePricePerSqft > 0 
            ? $averagePricePerSqft * $property->area_sqft 
            : $averageValue;

        $confidenceLevel = min(90, $count * 15); // Max 90% confidence

        return [
            'estimated_value' => round($estimatedValue, 2),
            'confidence_level' => $confidenceLevel,
            'method' => 'comparable_sales',
            'comparables_used' => $count,
            'price_per_sqft' => round($averagePricePerSqft, 2)
        ];
    }

    public function updatePropertyValue(Property $property, PropertyValuation $valuation): void
    {
        if ($valuation->valuation_type === 'market' && $valuation->status === 'active') {
            $property->update([
                'price' => $valuation->estimated_value ?? $valuation->market_value
            ]);
        }
    }
}