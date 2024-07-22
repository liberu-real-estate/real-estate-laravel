<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'buyer_id' => User::factory(),
            'seller_id' => User::factory(),
            'amount' => $this->faker->numberBetween(100000, 1000000),
            'transaction_date' => $this->faker->dateTimeThisYear(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
        ];
    }
}