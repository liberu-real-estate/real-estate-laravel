<?php

namespace Database\Factories;

use App\Models\RentalApplication;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RentalApplicationFactory extends Factory
{
    protected $model = RentalApplication::class;

    public function definition()
    {
        return [
            'property_id' => Property::factory(),
            'applicant_id' => User::factory(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'application_date' => $this->faker->dateTimeThisYear(),
            'desired_move_in_date' => $this->faker->dateTimeBetween('+1 week', '+2 months'),
            'monthly_income' => $this->faker->numberBetween(2000, 10000),
            'team_id' => Team::factory(),
        ];
    }
}