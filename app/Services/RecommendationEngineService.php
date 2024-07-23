<?php

namespace App\Services;

use App\Models\Property;
use App\Models\RecommendationEngine;
use App\Models\User;

class RecommendationEngineService
{
    public function getRecommendations(User $user, int $limit = 10)
    {
        $recommendationEngine = $user->recommendationEngine;

        if (!$recommendationEngine) {
            return Property::inRandomOrder()->limit($limit)->get();
        }

        // Implement your recommendation algorithm here
        // This is a simple example based on price range and property type
        $preferences = $recommendationEngine->preferences;
        $minPrice = $preferences['min_price'] ?? 0;
        $maxPrice = $preferences['max_price'] ?? PHP_INT_MAX;
        $propertyType = $preferences['property_type'] ?? null;

        $query = Property::whereBetween('price', [$minPrice, $maxPrice]);

        if ($propertyType) {
            $query->where('property_type', $propertyType);
        }

        return $query->inRandomOrder()->limit($limit)->get();
    }

    public function updateUserPreferences(User $user, array $data)
    {
        $recommendationEngine = $user->recommendationEngine ?? new RecommendationEngine();
        $recommendationEngine->user_id = $user->id;
        $recommendationEngine->preferences = array_merge($recommendationEngine->preferences ?? [], $data);
        $recommendationEngine->save();
    }

    public function updateSearchHistory(User $user, array $searchData)
    {
        $recommendationEngine = $user->recommendationEngine ?? new RecommendationEngine();
        $recommendationEngine->user_id = $user->id;
        $searchHistory = $recommendationEngine->search_history ?? [];
        array_unshift($searchHistory, $searchData);
        $recommendationEngine->search_history = array_slice($searchHistory, 0, 10); // Keep last 10 searches
        $recommendationEngine->save();
    }

    public function updateBrowsingBehavior(User $user, Property $property)
    {
        $recommendationEngine = $user->recommendationEngine ?? new RecommendationEngine();
        $recommendationEngine->user_id = $user->id;
        $browsingBehavior = $recommendationEngine->browsing_behavior ?? [];
        $browsingBehavior[] = $property->id;
        $recommendationEngine->browsing_behavior = array_slice(array_unique($browsingBehavior), 0, 20); // Keep last 20 unique property views
        $recommendationEngine->save();
    }
}