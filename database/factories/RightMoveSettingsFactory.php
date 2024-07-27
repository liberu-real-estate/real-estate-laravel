<?php

namespace Database\Factories;

use App\Models\RightMoveSettings;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class RightMoveSettingsFactory extends Factory
{
    protected $model = RightMoveSettings::class;

    public function definition()
    {
        return [
            'team_id' => Team::factory(),
            'api_key' => $this->faker->uuid,
            'branch_id' => $this->faker->numberBetween(1000, 9999),
            'channel' => $this->faker->randomElement(['sales', 'lettings']),
            'feed_url' => $this->faker->url,
            'is_active' => $this->faker->boolean,
        ];
    }
}