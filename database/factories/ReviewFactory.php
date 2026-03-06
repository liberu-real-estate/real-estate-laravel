<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reviewable_id' => Property::factory(),
            'reviewable_type' => Property::class,
            'rating' => $this->faker->numberBetween(1, 5),
            'title' => $this->faker->sentence(),
            'comment' => $this->faker->paragraph(),
            'review_date' => $this->faker->dateTimeThisYear(),
            'approved' => $this->faker->boolean(80),
            'moderation_status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
