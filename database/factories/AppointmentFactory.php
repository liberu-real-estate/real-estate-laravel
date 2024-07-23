<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'agent_id' => User::factory(),
            'property_id' => Property::factory(),
            'appointment_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'status' => $this->faker->randomElement(['scheduled', 'completed', 'cancelled']),
            'team_id' => Team::factory(),
            'appointment_type_id' => AppointmentType::factory(),
        ];
    }
}