<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'property_id' => Property::factory(),
            'buyer_id' => User::factory(),
            'seller_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 10000, 1000000),
            'date' => $this->faker->dateTimeThisYear(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
            // 'description' => $this->faker->sentence(),
        ];
    }
}