<?php

namespace Database\Factories;

use App\Models\Favorite;
use App\Models\User;
use App\Models\Property;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'property_id' => Property::factory(),
            'team_id' => Team::factory(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}