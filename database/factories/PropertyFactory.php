<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'address' => $this->faker->address,
            'price' => round($this->faker->numberBetween(150000, 500000), -3),
            'bedrooms' => $this->faker->numberBetween(1, 5),
            'bathrooms' => $this->faker->numberBetween(1, 3),
            'square_feet' => $this->faker->numberBetween(500, 5000),
            'year_built' => $this->faker->year,
            'property_type' => $this->faker->randomElement(['House', 'Apartment', 'Condo', 'Townhouse']),
            'status' => $this->faker->randomElement(['For Sale', 'For Rent', 'Sold', 'Rented']),
            'team_id' => Team::factory(),
        ];
    }
}