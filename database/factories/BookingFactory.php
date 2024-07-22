<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'date' => $this->faker->date(),
            'time' => $this->faker->time(),
            'staff_id' => \App\Models\User::factory(),
            'user_id' => \App\Models\User::factory(),
            'notes' => $this->faker->optional()->sentence,
            'property_id' => \App\Models\Property::factory(),
            'name' => $this->faker->name,
            'contact' => $this->faker->phoneNumber,
        ];
    }
}