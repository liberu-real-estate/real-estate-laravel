<?php

namespace App\Services;

class HomeValuationService
{
    /**
     * Calculate home valuation based on property details
     * 
     * @param float $propertySize Square footage/meters
     * @param int $bedrooms Number of bedrooms
     * @param int $bathrooms Number of bathrooms
     * @param int $yearBuilt Year the property was built
     * @param string $propertyType Type of property (detached, semi-detached, terraced, apartment, bungalow)
     * @param string $condition Property condition (excellent, good, fair, poor)
     * @param string $location Location quality (prime, good, average, below-average)
     * @param float $basePrice Base price per square unit in the area
     * @return array
     */
    public function calculateHomeValuation(
        float $propertySize,
        int $bedrooms,
        int $bathrooms,
        int $yearBuilt,
        string $propertyType,
        string $condition,
        string $location,
        float $basePrice = 3000
    ): array {
        // Base value calculation
        $baseValue = $propertySize * $basePrice;
        
        // Property type multiplier
        $typeMultiplier = $this->getPropertyTypeMultiplier($propertyType);
        
        // Condition multiplier
        $conditionMultiplier = $this->getConditionMultiplier($condition);
        
        // Location multiplier
        $locationMultiplier = $this->getLocationMultiplier($location);
        
        // Age adjustment
        $ageAdjustment = $this->getAgeAdjustment($yearBuilt);
        
        // Bedrooms and bathrooms bonus
        $roomBonus = $this->getRoomBonus($bedrooms, $bathrooms);
        
        // Calculate estimated value
        $estimatedValue = $baseValue * $typeMultiplier * $conditionMultiplier * $locationMultiplier * $ageAdjustment;
        $estimatedValue += $roomBonus;
        
        // Calculate confidence level (70-95% based on factors)
        $confidenceLevel = $this->calculateConfidenceLevel($propertyType, $condition, $location);
        
        // Calculate value range (±10% for uncertainty)
        $valueRange = $estimatedValue * 0.10;
        $minValue = $estimatedValue - $valueRange;
        $maxValue = $estimatedValue + $valueRange;
        
        return [
            'estimated_value' => round($estimatedValue, 2),
            'min_value' => round($minValue, 2),
            'max_value' => round($maxValue, 2),
            'confidence_level' => $confidenceLevel,
            'property_size' => $propertySize,
            'bedrooms' => $bedrooms,
            'bathrooms' => $bathrooms,
            'year_built' => $yearBuilt,
            'property_age' => date('Y') - $yearBuilt,
            'property_type' => $propertyType,
            'condition' => $condition,
            'location' => $location,
            'base_price_per_unit' => $basePrice,
            'breakdown' => [
                'base_value' => round($baseValue, 2),
                'type_multiplier' => $typeMultiplier,
                'condition_multiplier' => $conditionMultiplier,
                'location_multiplier' => $locationMultiplier,
                'age_adjustment' => $ageAdjustment,
                'room_bonus' => round($roomBonus, 2),
            ]
        ];
    }
    
    private function getPropertyTypeMultiplier(string $propertyType): float
    {
        return match ($propertyType) {
            'detached' => 1.3,
            'semi-detached' => 1.1,
            'terraced' => 1.0,
            'apartment' => 0.9,
            'bungalow' => 1.15,
            default => 1.0,
        };
    }
    
    private function getConditionMultiplier(string $condition): float
    {
        return match ($condition) {
            'excellent' => 1.2,
            'good' => 1.1,
            'fair' => 1.0,
            'poor' => 0.85,
            default => 1.0,
        };
    }
    
    private function getLocationMultiplier(string $location): float
    {
        return match ($location) {
            'prime' => 1.4,
            'good' => 1.2,
            'average' => 1.0,
            'below-average' => 0.8,
            default => 1.0,
        };
    }
    
    private function getAgeAdjustment(int $yearBuilt): float
    {
        $age = date('Y') - $yearBuilt;
        
        if ($age < 0) {
            return 1.0; // Future date, no adjustment
        } elseif ($age <= 5) {
            return 1.1; // New build premium
        } elseif ($age <= 10) {
            return 1.05; // Nearly new
        } elseif ($age <= 30) {
            return 1.0; // Standard
        } elseif ($age <= 50) {
            return 0.95; // Older property
        } else {
            return 0.9; // Very old property
        }
    }
    
    private function getRoomBonus(int $bedrooms, int $bathrooms): float
    {
        $bedroomBonus = max(0, ($bedrooms - 2)) * 15000; // Bonus for each bedroom over 2
        $bathroomBonus = max(0, ($bathrooms - 1)) * 8000; // Bonus for each bathroom over 1
        
        return $bedroomBonus + $bathroomBonus;
    }
    
    private function calculateConfidenceLevel(string $propertyType, string $condition, string $location): int
    {
        $confidence = 85; // Base confidence
        
        // Adjust based on property type (some types more standardized)
        if (in_array($propertyType, ['apartment', 'terraced'])) {
            $confidence += 5;
        }
        
        // Adjust based on condition (excellent/good conditions are easier to value)
        if (in_array($condition, ['excellent', 'good'])) {
            $confidence += 3;
        } elseif ($condition === 'poor') {
            $confidence -= 5;
        }
        
        // Adjust based on location (prime locations have more data)
        if ($location === 'prime') {
            $confidence += 5;
        } elseif ($location === 'below-average') {
            $confidence -= 3;
        }
        
        // Ensure confidence is within 70-95% range
        return max(70, min(95, $confidence));
    }
}
