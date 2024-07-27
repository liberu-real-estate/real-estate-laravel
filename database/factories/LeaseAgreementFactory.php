<?php

namespace Database\Factories;

use App\Models\LeaseAgreement;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaseAgreementFactory extends Factory
{
    protected $model = LeaseAgreement::class;

    public function definition()
    {
        return [
            'property_id' => Property::factory(),
            'tenant_id' => User::factory(),
            'start_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+1 year', '+2 years'),
            'rent_amount' => $this->faker->numberBetween(500, 5000),
            'security_deposit' => $this->faker->numberBetween(500, 2000),
            'status' => $this->faker->randomElement(['draft', 'active', 'expired']),
            'team_id' => Team::factory(),
            'landlord_id' => User::factory(),
            'payment_frequency' => $this->faker->randomElement(['Monthly', 'Quarterly', 'Yearly']),
            'terms_and_conditions' => $this->faker->paragraphs(3, true),
        ];
    }
}