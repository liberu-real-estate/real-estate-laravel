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
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'rent_amount' => $this->faker->randomFloat(2, 500, 5000),
            'status' => $this->faker->randomElement(['active', 'expired', 'terminated']),
        ];
    }
}