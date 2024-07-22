<?php

namespace Database\Factories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition()
    {
        return [
            'lead_id' => \App\Models\Lead::factory(),
            'user_id' => \App\Models\User::factory(),
            'type' => $this->faker->word,
            'description' => $this->faker->sentence,
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'completed_at' => $this->faker->optional()->dateTimeBetween('now', '+2 months'),
        ];
    }
}