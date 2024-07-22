<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'lead_id' => Lead::factory(),
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['call', 'email', 'meeting']),
            'description' => $this->faker->sentence(),
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'completed_at' => $this->faker->optional()->dateTimeBetween('now', '+2 months'),
        ];
    }
}