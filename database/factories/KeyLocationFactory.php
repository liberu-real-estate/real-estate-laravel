<?php

namespace Database\Factories;

use App\Models\KeyLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

class KeyLocationFactory extends Factory
{
    protected $model = KeyLocation::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
        ];
    }
}