<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'type' => $this->faker->randomElement(['created', 'updated', 'deleted']),
            'subject_type' => $this->faker->randomElement(['App\Models\Property', 'App\Models\Lead', 'App\Models\Appointment']),
            'subject_id' => $this->faker->numberBetween(1, 100),
            'description' => $this->faker->sentence,
            'created_at' => $this->faker->dateTimeThisYear,
            'updated_at' => $this->faker->dateTimeThisYear,
        ];
    }
}