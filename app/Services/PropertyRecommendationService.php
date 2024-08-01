<?php

namespace App\Services;

use App\Models\User;
use App\Models\Property;
use App\Models\Activity;
use App\Models\Favorite;
use Illuminate\Support\Facades\Cache;

class PropertyRecommendationService
{
    public function getRecommendations(User $user, $limit = 6)
    {
        return Cache::remember("user_{$user->id}_recommendations", now()->addHours(1), function () use ($user, $limit) {
            $userPreferences = $this->getUserPreferences($user);
            $recommendedProperties = Property::query()
                ->with(['images', 'features'])
                ->where('status', 'approved')
                ->where('id', '!=', $user->viewed_properties->pluck('id'))
                ->orderByRaw($this->buildOrderByClause($userPreferences))
                ->limit($limit)
                ->get();

            return $recommendedProperties;
        });
    }

    private function getUserPreferences(User $user)
    {
        $viewedProperties = Activity::where('user_id', $user->id)
            ->where('type', 'property_view')
            ->with('property')
            ->get();

        $favorites = Favorite::where('user_id', $user->id)->with('property')->get();

        // Calculate average price, bedrooms, bathrooms, and area of viewed and favorited properties
        $preferences = [
            'avg_price' => $viewedProperties->merge($favorites)->avg('property.price'),
            'avg_bedrooms' => $viewedProperties->merge($favorites)->avg('property.bedrooms'),
            'avg_bathrooms' => $viewedProperties->merge($favorites)->avg('property.bathrooms'),
            'avg_area' => $viewedProperties->merge($favorites)->avg('property.area_sqft'),
            'preferred_locations' => $viewedProperties->merge($favorites)->pluck('property.location')->countBy()->sortDesc()->keys()->take(3),
            'preferred_features' => $viewedProperties->merge($favorites)->flatMap(function ($item) {
                return $item->property->features->pluck('feature_name');
            })->countBy()->sortDesc()->keys()->take(5),
        ];

        return $preferences;
    }

    private function buildOrderByClause($preferences)
    {
        $clause = [];
        $clause[] = "ABS(price - {$preferences['avg_price']})";
        $clause[] = "ABS(bedrooms - {$preferences['avg_bedrooms']})";
        $clause[] = "ABS(bathrooms - {$preferences['avg_bathrooms']})";
        $clause[] = "ABS(area_sqft - {$preferences['avg_area']})";

        $locationScores = implode(' + ', array_map(function ($location) {
            return "CASE WHEN location LIKE '%{$location}%' THEN 1 ELSE 0 END";
        }, $preferences['preferred_locations']->toArray()));
        $clause[] = "({$locationScores}) DESC";

        return implode(', ', $clause);
    }

    public function getRecommendedProperties(User $user, $limit = 5)
    {
        // Get properties the user has interacted with
        $userInteractions = $this->getUserInteractions($user);

        // Get similar users based on property interactions
        $similarUsers = $this->getSimilarUsers($user, $userInteractions);

        // Get properties liked by similar users
        $recommendedProperties = $this->getPropertiesFromSimilarUsers($similarUsers, $userInteractions, $limit);

        return $recommendedProperties;
    }

    private function getUserInteractions(User $user)
    {
        $favorites = $user->favorites()->pluck('property_id')->toArray();
        $views = $user->activities()->where('type', 'property_view')->pluck('property_id')->toArray();

        return array_unique(array_merge($favorites, $views));
    }

    private function getSimilarUsers(User $user, array $userInteractions)
    {
        return User::whereHas('favorites', function ($query) use ($userInteractions) {
            $query->whereIn('property_id', $userInteractions);
        })->orWhereHas('activities', function ($query) use ($userInteractions) {
            $query->whereIn('property_id', $userInteractions)
                  ->where('type', 'property_view');
        })->where('id', '!=', $user->id)
          ->withCount(['favorites', 'activities'])
          ->orderByDesc('favorites_count')
          ->orderByDesc('activities_count')
          ->limit(10)
          ->get();
    }

    private function getPropertiesFromSimilarUsers($similarUsers, array $userInteractions, $limit)
    {
        $similarUserIds = $similarUsers->pluck('id')->toArray();

        return Property::whereHas('favorites', function ($query) use ($similarUserIds) {
            $query->whereIn('user_id', $similarUserIds);
        })->orWhereHas('activities', function ($query) use ($similarUserIds) {
            $query->whereIn('user_id', $similarUserIds)
                  ->where('type', 'property_view');
        })->whereNotIn('id', $userInteractions)
          ->withCount(['favorites', 'activities'])
          ->orderByDesc('favorites_count')
          ->orderByDesc('activities_count')
          ->limit($limit)
          ->get();
    }
}