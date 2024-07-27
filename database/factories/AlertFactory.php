<?php

namespace Database\Factories;

use App\Models\Alert;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlertFactory extends Factory
{
    protected $model = Alert::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'type' => $this->faker->randomElement(['info', 'warning', 'error', 'success']),
            'user_id' => \App\Models\User::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}