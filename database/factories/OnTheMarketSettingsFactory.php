<?php

namespace Database\Factories;

use App\Models\OnTheMarketSettings;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class OnTheMarketSettingsFactory extends Factory
{
    protected $model = OnTheMarketSettings::class;

    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'api_key' => $this->faker->uuid,
            'base_uri' => $this->faker->url,
            'sync_frequency' => $this->faker->randomElement(['hourly', 'daily', 'weekly']),
            'is_active' => $this->faker->boolean,
        ];
    }
}
