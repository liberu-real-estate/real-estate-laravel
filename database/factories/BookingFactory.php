<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'time' => $this->faker->time(),
            'staff_id' => User::factory(),
            'user_id' => User::factory(),
            'notes' => $this->faker->optional()->sentence(),
            'property_id' => Property::factory(),
            'name' => $this->faker->name(),
            'contact' => $this->faker->phoneNumber(),
        ];
    }
}