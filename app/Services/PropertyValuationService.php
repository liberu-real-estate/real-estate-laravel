<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Http;

class PropertyValuationService
{
    private $valPalApiKey;

    public function __construct()
    {
        $this->valPalApiKey = config('services.valpal.api_key');
    }

    public function calculateValuation(Property $property, array $additionalData = []): array
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

        // Adjust for number of rooms
        $roomsFactor = $this->getRoomsFactor($property->bedrooms, $property->bathrooms);
        $baseValue *= $roomsFactor;

        // Adjust for market trends (simplified)
        $marketTrendFactor = $additionalData['market_trend_factor'] ?? 1;
        $baseValue *= $marketTrendFactor;

        // Get ValPal API valuation
        $valPalValuation = $this->getValPalValuation($property);

        // Calculate final valuation range
        $lowerBound = min($baseValue, $valPalValuation) * 0.9;
        $upperBound = max($baseValue, $valPalValuation) * 1.1;

        return [
            'estimated_value' => round(($lowerBound + $upperBound) / 2, 2),
            'range_low' => round($lowerBound, 2),
            'range_high' => round($upperBound, 2),
        ];
    }

    private function getRoomsFactor(int $bedrooms, int $bathrooms): float
    {
        return 1 + (($bedrooms + $bathrooms) * 0.05);
    }

    private function getValPalValuation(Property $property): float
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->valPalApiKey,
        ])->post('https://api.valpal.com/valuation', [
            'postcode' => $property->postal_code,
            'property_type' => $property->property_type,
            'num_bedrooms' => $property->bedrooms,
            'num_bathrooms' => $property->bathrooms,
            'floor_area' => $property->area_sqft,
        ]);

        if ($response->successful()) {
            return $response->json('estimated_value');
        }

        // Return the base price if API call fails
        return $property->price;
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