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

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'property_id' => \App\Models\Property::factory(),
            'appointment_type_id' => \App\Models\AppointmentType::factory(),
            'start_time' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_time' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'notes' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['scheduled', 'completed', 'cancelled']),
            'created_at' => $this->faker->dateTimeThisYear,
            'updated_at' => $this->faker->dateTimeThisYear,
        ];
    }
}