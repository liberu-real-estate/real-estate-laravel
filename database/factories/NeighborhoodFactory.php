<?php

namespace Database\Factories;

use App\Models\Neighborhood;
use Illuminate\Database\Eloquent\Factories\Factory;

class NeighborhoodFactory extends Factory
{
    protected $model = Neighborhood::class;

    public function definition()
    {
        return [
            'name' => $this->faker->city,
            'description' => $this->faker->paragraph,
            'zip_code' => $this->faker->postcode,
        ];
    }
}