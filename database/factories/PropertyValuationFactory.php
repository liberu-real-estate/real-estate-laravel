<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyValuation>
 */
class PropertyValuationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'valuation_type' => $this->faker->randomElement(['market', 'rental', 'insurance', 'mortgage', 'neural_network']),
            'estimated_value' => $this->faker->randomFloat(2, 100000, 2000000),
            'market_value' => $this->faker->randomFloat(2, 100000, 2000000),
            'rental_value' => $this->faker->randomFloat(2, 500, 5000),
            'valuation_date' => $this->faker->date(),
            'valuer_name' => $this->faker->name(),
            'valuer_company' => $this->faker->company(),
            'valuation_method' => $this->faker->randomElement(['comparative', 'income', 'cost', 'neural_network']),
            'confidence_level' => $this->faker->numberBetween(0, 100),
            'status' => 'active',
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
        ];
    }
}
