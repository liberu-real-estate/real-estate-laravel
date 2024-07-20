<?php

namespace App\Services;

use App\Models\Property;

class PropertyValuationService
{
    public function calculateValuation(Property $property, array $additionalData = []): float
    {
        // Basic valuation algorithm
        $baseValue = $property->price;

        // Adjust for location
        $locationFactor = $this->getLocationFactor($property->location);
        $baseValue *= $locationFactor;

        // Adjust for property type
        $propertyTypeFactor = $this->getPropertyTypeFactor($property->property_type);
        $baseValue *= $propertyTypeFactor;

        // Adjust for area
        $areaFactor = $this->getAreaFactor($property->area_sqft);
        $baseValue *= $areaFactor;

        // Adjust for age
        $ageFactor = $this->getAgeFactor($property->year_built);
        $baseValue *= $ageFactor;

        // Adjust for market trends (simplified)
        $marketTrendFactor = $additionalData['market_trend_factor'] ?? 1;
        $baseValue *= $marketTrendFactor;

        return round($baseValue, 2);
    }

    private function getLocationFactor(string $location): float
    {
        // Simplified location factor calculation
        $locationFactors = [
            'London' => 1.5,
            'Manchester' => 1.2,
            'Birmingham' => 1.1,
            // Add more locations as needed
        ];

        return $locationFactors[$location] ?? 1.0;
    }

    private function getPropertyTypeFactor(string $propertyType): float
    {
        $propertyTypeFactors = [
            'house' => 1.2,
            'apartment' => 1.0,
            'condo' => 1.1,
        ];

        return $propertyTypeFactors[$propertyType] ?? 1.0;
    }

    private function getAreaFactor(float $areaSqFt): float
    {
        // Simplified area factor calculation
        if ($areaSqFt < 500) return 0.8;
        if ($areaSqFt < 1000) return 1.0;
        if ($areaSqFt < 2000) return 1.2;
        return 1.4;
    }

    private function getAgeFactor(int $yearBuilt): float
    {
        $age = date('Y') - $yearBuilt;
        if ($age < 5) return 1.1;
        if ($age < 20) return 1.0;
        if ($age < 50) return 0.9;
        return 0.8;
    }
}