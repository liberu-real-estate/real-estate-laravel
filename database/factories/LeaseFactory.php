<?php

namespace Database\Factories;

use App\Models\Lease;
use App\Models\Property;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaseFactory extends Factory
{
    protected $model = Lease::class;

    public function definition()
    {
        return [
            'property_id' => Property::factory(),
            'tenant_id' => Tenant::factory(),
            'start_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
            'rent_amount' => $this->faker->numberBetween(500, 5000),
            'security_deposit' => $this->faker->numberBetween(500, 2000),
            'status' => $this->faker->randomElement(['active', 'pending', 'expired']),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}