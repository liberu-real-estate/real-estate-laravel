<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'team_id' => Team::factory(),
        ];
    }
}