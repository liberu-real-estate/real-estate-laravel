<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ZooplaSettings>
 */
class ZooplaSettingsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'api_key' => $this->faker->uuid,
            'is_active' => $this->faker->boolean,
            'feed_id' => $this->faker->numberBetween(1000, 9999),
        ];
    }
}
