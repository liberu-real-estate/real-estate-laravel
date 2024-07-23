<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
            'location' => $this->faker->address,
            'price' => $this->faker->numberBetween(100000, 1000000),
            'bedrooms' => $this->faker->numberBetween(1, 5),
            'bathrooms' => $this->faker->numberBetween(1, 3),
            'area_sqft' => $this->faker->numberBetween(500, 5000),
            'year_built' => $this->faker->year,
            'property_type' => $this->faker->randomElement(['House', 'Apartment', 'Condo', 'Townhouse']),
            'status' => $this->faker->randomElement(['For Sale', 'For Rent', 'Sold', 'Rented']),
            'list_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'sold_date' => $this->faker->optional(0.3)->dateTimeBetween('-6 months', 'now'),
            'user_id' => User::factory(),
            'team_id' => Team::factory(),
            'agent_id' => User::factory(),
            'virtual_tour_url' => $this->faker->optional()->url,
            'is_featured' => $this->faker->boolean(20),
            'rightmove_id' => $this->faker->optional()->uuid,
            'zoopla_id' => $this->faker->optional()->uuid,
            'onthemarket_id' => $this->faker->optional()->uuid,
            'last_synced_at' => $this->faker->optional()->dateTimeThisMonth,
        ];
    }
}