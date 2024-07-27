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

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'type' => $this->faker->randomElement(['email', 'sms', 'push']),
            'frequency' => $this->faker->randomElement(['daily', 'weekly', 'monthly']),
            'criteria' => json_encode([
                'property_type' => $this->faker->randomElement(['apartment', 'house', 'condo']),
                'min_price' => $this->faker->numberBetween(50000, 200000),
                'max_price' => $this->faker->numberBetween(200001, 1000000),
                'bedrooms' => $this->faker->numberBetween(1, 5),
                'bathrooms' => $this->faker->numberBetween(1, 3),
            ]),
            'is_active' => $this->faker->boolean,
            'created_at' => $this->faker->dateTimeThisYear,
            'updated_at' => $this->faker->dateTimeThisYear,
        ];
    }
}