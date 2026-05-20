<?php

namespace App\Services;

use App\Models\Buyer;
use App\Models\Property;
use App\Models\PropertyMatch;
use Illuminate\Support\Collection;

class PropertyMatchingService
{
    public function findMatches(Buyer $buyer, int $limit = 10): Collection
    {
        $criteria = $buyer->search_criteria ?? [];

        $query = Property::where('status', 'available')
            ->where('team_id', $buyer->team_id);

        // Apply buyer criteria filters
        if (isset($criteria['min_price']) || isset($criteria['max_price'])) {
            $query->whereBetween('price', [
                $criteria['min_price'] ?? 0,
                $criteria['max_price'] ?? PHP_INT_MAX
            ]);
        }

        if (isset($criteria['property_type'])) {
            $query->where('property_type', $criteria['property_type']);
        }

        if (isset($criteria['min_bedrooms'])) {
            $query->where('bedrooms', '>=', $criteria['min_bedrooms']);
        }

        if (isset($criteria['max_bedrooms'])) {
            $query->where('bedrooms', '<=', $criteria['max_bedrooms']);
        }

        if (isset($criteria['min_bathrooms'])) {
            $query->where('bathrooms', '>=', $criteria['min_bathrooms']);
        }

        if (isset($criteria['location']) && !empty($criteria['location'])) {
            $query->where('location', 'like', '%' . $criteria['location'] . '%');
        }

        if (isset($criteria['postal_codes']) && is_array($criteria['postal_codes'])) {
            $query->whereIn('postal_code', $criteria['postal_codes']);
        }

        $properties = $query->limit($limit * 2)->get(); // Get more to calculate scores

        return $this->calculateMatchScores($buyer, $properties)->take($limit);
    }

    public function calculateMatchScore(Buyer $buyer, Property $property): array
    {
        $criteria = $buyer->search_criteria ?? [];
        $scores = [];

        // Price match (30% weight)
        $scores['price_match'] = $this->calculatePriceMatch($criteria, $property->price);

        // Location match (25% weight)
        $scores['location_match'] = $this->calculateLocationMatch($criteria, $property);

        // Size match (20% weight)
        $scores['size_match'] = $this->calculateSizeMatch($criteria, $property);

        // Features match (15% weight)
        $scores['features_match'] = $this->calculateFeaturesMatch($criteria, $property);

        // Type match (10% weight)
        $scores['type_match'] = $this->calculateTypeMatch($criteria, $property);

        // Calculate overall score
        $overallScore = (
            $scores['price_match'] * 0.30 +
            $scores['location_match'] * 0.25 +
            $scores['size_match'] * 0.20 +
            $scores['features_match'] * 0.15 +
            $scores['type_match'] * 0.10
        );

        return [
            'match_score' => round($overallScore, 2),
            'price_match' => round($scores['price_match'], 2),
            'location_match' => round($scores['location_match'], 2),
            'size_match' => round($scores['size_match'], 2),
            'features_match' => round($scores['features_match'], 2),
            'type_match' => round($scores['type_match'], 2),
        ];
    }

    public function createMatch(Buyer $buyer, Property $property, array $scores): PropertyMatch
    {
        // Check if match already exists
        $existingMatch = PropertyMatch::where('buyer_id', $buyer->id)
            ->where('property_id', $property->id)
            ->first();

        if ($existingMatch) {
            $existingMatch->update($scores);
            return $existingMatch;
        }

        return PropertyMatch::create(array_merge([
            'buyer_id' => $buyer->id,
            'property_id' => $property->id,
            'team_id' => $buyer->team_id,
            'auto_generated' => true,
            'match_criteria' => $buyer->search_criteria
        ], $scores));
    }

    public function generateMatchesForBuyer(Buyer $buyer): Collection
    {
        $properties = $this->findMatches($buyer, 20);
        $matches = collect();

        foreach ($properties as $property) {
            $scores = $this->calculateMatchScore($buyer, $property);

            // Only create matches with score > 50%
            if ($scores['match_score'] >= 50) {
                $match = $this->createMatch($buyer, $property, $scores);
                $matches->push($match);
            }
        }

        return $matches;
    }

    public function generateMatchesForProperty(Property $property): Collection
    {
        $buyers = Buyer::where('status', 'active')
            ->where('team_id', $property->team_id)
            ->whereNotNull('search_criteria')
            ->get();

        $matches = collect();

        foreach ($buyers as $buyer) {
            $scores = $this->calculateMatchScore($buyer, $property);

            // Only create matches with score > 50%
            if ($scores['match_score'] >= 50) {
                $match = $this->createMatch($buyer, $property, $scores);
                $matches->push($match);
            }
        }

        return $matches;
    }

    private function calculateMatchScores(Buyer $buyer, Collection $properties): Collection
    {
        return $properties->map(function ($property) use ($buyer) {
            $scores = $this->calculateMatchScore($buyer, $property);
            $property->match_score = $scores['match_score'];
            $property->match_details = $scores;
            return $property;
        })->sortByDesc('match_score');
    }

    private function calculatePriceMatch(array $criteria, float $price): float
    {
        $minPrice = $criteria['min_price'] ?? 0;
        $maxPrice = $criteria['max_price'] ?? PHP_INT_MAX;

        if ($price >= $minPrice && $price <= $maxPrice) {
            // Perfect match if within range
            $range = $maxPrice - $minPrice;
            $ideal = $minPrice + ($range * 0.5); // Middle of range is ideal

            if ($range > 0) {
                $deviation = abs($price - $ideal) / ($range * 0.5);
                return max(80, 100 - ($deviation * 20)); // 80-100% for within range
            }
            return 100;
        }

        // Partial match if close to range
        if ($price < $minPrice) {
            $deviation = ($minPrice - $price) / $minPrice;
            return max(0, 80 - ($deviation * 80));
        } else {
            $deviation = ($price - $maxPrice) / $maxPrice;
            return max(0, 80 - ($deviation * 80));
        }
    }

    private function calculateLocationMatch(array $criteria, Property $property): float
    {
        $score = 50; // Base score

        if (isset($criteria['location']) && !empty($criteria['location'])) {
            $searchLocation = strtolower($criteria['location']);
            $propertyLocation = strtolower($property->location);

            if (str_contains($propertyLocation, $searchLocation)) {
                $score += 30;
            }
        }

        if (isset($criteria['postal_codes']) && is_array($criteria['postal_codes'])) {
            if (in_array($property->postal_code, $criteria['postal_codes'])) {
                $score += 20;
            }
        }

        return min(100, $score);
    }

    private function calculateSizeMatch(array $criteria, Property $property): float
    {
        $score = 0;
        $factors = 0;

        // Bedrooms match
        if (isset($criteria['min_bedrooms']) || isset($criteria['max_bedrooms'])) {
            $minBed = $criteria['min_bedrooms'] ?? 0;
            $maxBed = $criteria['max_bedrooms'] ?? 10;

            if ($property->bedrooms >= $minBed && $property->bedrooms <= $maxBed) {
                $score += 50;
            } else {
                $deviation = min(abs($property->bedrooms - $minBed), abs($property->bedrooms - $maxBed));
                $score += max(0, 50 - ($deviation * 15));
            }
            $factors++;
        }

        // Bathrooms match
        if (isset($criteria['min_bathrooms'])) {
            if ($property->bathrooms >= $criteria['min_bathrooms']) {
                $score += 30;
            } else {
                $deviation = $criteria['min_bathrooms'] - $property->bathrooms;
                $score += max(0, 30 - ($deviation * 10));
            }
            $factors++;
        }

        // Area match
        if (isset($criteria['min_area']) || isset($criteria['max_area'])) {
            $minArea = $criteria['min_area'] ?? 0;
            $maxArea = $criteria['max_area'] ?? PHP_INT_MAX;

            if ($property->area_sqft >= $minArea && $property->area_sqft <= $maxArea) {
                $score += 20;
            }
            $factors++;
        }

        return $factors > 0 ? min(100, $score / $factors * 2) : 50;
    }

    private function calculateFeaturesMatch(array $criteria, Property $property): float
    {
        $score = 50; // Base score

        if (isset($criteria['required_features']) && is_array($criteria['required_features'])) {
            $propertyFeatures = $property->features->pluck('feature_name')->toArray();
            $requiredFeatures = $criteria['required_features'];

            $matchedFeatures = array_intersect($propertyFeatures, $requiredFeatures);
            $matchPercentage = count($requiredFeatures) > 0 
                ? count($matchedFeatures) / count($requiredFeatures) 
                : 1;

            $score = 50 + ($matchPercentage * 50);
        }

        return min(100, $score);
    }

    private function calculateTypeMatch(array $criteria, Property $property): float
    {
        if (isset($criteria['property_type'])) {
            return $criteria['property_type'] === $property->property_type ? 100 : 0;
        }

        return 50; // Neutral if no preference specified
    }
}