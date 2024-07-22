<?php

namespace Database\Factories;

use App\Models\ZooplaSettings;
use Illuminate\Database\Eloquent\Factories\Factory;

class ZooplaSettingsFactory extends Factory
{
    protected $model = ZooplaSettings::class;

    public function definition(): array
    {
        return [
            'api_key' => $this->faker->uuid(),
            'last_sync' => $this->faker->dateTimeThisYear(),
            'sync_interval' => $this->faker->numberBetween(1, 24),
        ];
    }
}