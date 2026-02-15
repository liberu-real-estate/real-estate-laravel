<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Neighborhood;

class NeighborhoodSeeder extends Seeder
{
    public function run()
    {
        $neighborhoods = [
            [
                'name' => 'Downtown District',
                'description' => 'A vibrant urban area with excellent amenities and public transport connections.',
                'schools' => [
                    ['name' => 'Central High School', 'rating' => 9],
                    ['name' => 'Downtown Elementary', 'rating' => 8],
                ],
                'amenities' => ['Shopping Centers', 'Restaurants', 'Parks', 'Public Transport', 'Gyms'],
                'crime_rate' => 'Low',
                'median_income' => 85000,
                'population' => 35000,
                'walk_score' => 95,
                'transit_score' => 90,
                'last_updated' => now(),
            ],
            [
                'name' => 'Suburban Hills',
                'description' => 'Quiet residential area with family-friendly environment and good schools.',
                'schools' => [
                    ['name' => 'Hillside Academy', 'rating' => 10],
                    ['name' => 'Green Valley School', 'rating' => 9],
                ],
                'amenities' => ['Parks', 'Shopping Centers', 'Community Center', 'Libraries'],
                'crime_rate' => 'Very Low',
                'median_income' => 95000,
                'population' => 22000,
                'walk_score' => 65,
                'transit_score' => 55,
                'last_updated' => now(),
            ],
            [
                'name' => 'Riverside',
                'description' => 'Scenic area along the river with recreational facilities and waterfront access.',
                'schools' => [
                    ['name' => 'Riverside Secondary', 'rating' => 8],
                    ['name' => 'Waterfront Primary', 'rating' => 7],
                ],
                'amenities' => ['Parks', 'River Access', 'Cycling Paths', 'Restaurants', 'Marina'],
                'crime_rate' => 'Low',
                'median_income' => 75000,
                'population' => 18000,
                'walk_score' => 80,
                'transit_score' => 70,
                'last_updated' => now(),
            ],
            [
                'name' => 'Tech Quarter',
                'description' => 'Modern business district with tech startups and innovative companies.',
                'schools' => [
                    ['name' => 'Innovation Academy', 'rating' => 9],
                    ['name' => 'STEM School', 'rating' => 10],
                ],
                'amenities' => ['Coworking Spaces', 'Restaurants', 'Coffee Shops', 'Public Transport', 'Tech Hubs'],
                'crime_rate' => 'Low',
                'median_income' => 105000,
                'population' => 28000,
                'walk_score' => 88,
                'transit_score' => 85,
                'last_updated' => now(),
            ],
            [
                'name' => 'Old Town',
                'description' => 'Historic neighborhood with charming architecture and cultural attractions.',
                'schools' => [
                    ['name' => 'Heritage High', 'rating' => 8],
                    ['name' => 'Old Town Elementary', 'rating' => 7],
                ],
                'amenities' => ['Museums', 'Art Galleries', 'Historic Sites', 'Restaurants', 'Boutiques'],
                'crime_rate' => 'Medium',
                'median_income' => 68000,
                'population' => 15000,
                'walk_score' => 92,
                'transit_score' => 75,
                'last_updated' => now(),
            ],
        ];

        foreach ($neighborhoods as $neighborhood) {
            Neighborhood::create($neighborhood);
        }
    }
}
