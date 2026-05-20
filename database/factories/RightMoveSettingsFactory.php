<?php

namespace Database\Factories;

use App\Models\Branch;
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
            'branch_id' => Branch::factory(),
            'api_key' => $this->faker->uuid,
            'channel' => $this->faker->randomElement(['sales', 'lettings']),
            'feed_type' => $this->faker->randomElement(['full', 'incremental']),
            'feed_url' => $this->faker->url,
            'is_active' => $this->faker->boolean,
        ];
    }
}