<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->address(),
            'price' => $this->faker->numberBetween(100000, 1000000),
            'bedrooms' => $this->faker->numberBetween(1, 5),
            'bathrooms' => $this->faker->numberBetween(1, 3),
            'area_sqft' => $this->faker->numberBetween(500, 5000),
            'year_built' => $this->faker->year(),
            'property_type' => $this->faker->randomElement(['house', 'apartment', 'condo']),
            'status' => $this->faker->randomElement(['for_sale', 'for_rent', 'sold']),
            'list_date' => $this->faker->date(),
            'sold_date' => $this->faker->optional()->date(),
            'user_id' => User::factory(),
            'agent_id' => User::factory(),
            'virtual_tour_url' => $this->faker->optional()->url(),
            'is_featured' => $this->faker->boolean(),
            'zoopla_id' => $this->faker->optional()->uuid(),
            'onthemarket_id' => $this->faker->optional()->uuid(),
            'last_synced_at' => $this->faker->optional()->dateTime(),
        ];
    }
}