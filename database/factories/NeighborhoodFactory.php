<?php

namespace Database\Factories;

use App\Models\Neighborhood;
use Illuminate\Database\Eloquent\Factories\Factory;

class NeighborhoodFactory extends Factory
{
    protected $model = Neighborhood::class;

    public function definition()
    {
        return [
            'name' => $this->faker->city,
            'description' => $this->faker->paragraph,
            'schools' => [
                [
                    'name' => $this->faker->company . ' School',
                    'rating' => $this->faker->numberBetween(5, 10),
                ],
                [
                    'name' => $this->faker->company . ' High School',
                    'rating' => $this->faker->numberBetween(5, 10),
                ],
            ],
            'amenities' => [
                'Parks',
                'Shopping Centers',
                'Restaurants',
                'Public Transport',
            ],
            'crime_rate' => $this->faker->randomElement(['Low', 'Medium', 'High']),
            'median_income' => $this->faker->numberBetween(40000, 120000),
            'population' => $this->faker->numberBetween(5000, 50000),
            'walk_score' => $this->faker->numberBetween(40, 100),
            'transit_score' => $this->faker->numberBetween(30, 100),
            'last_updated' => now(),
        ];
    }
}
