<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 100, 5000),
            'payment_date' => $this->faker->dateTimeThisYear(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'refunded']),
            'payment_method' => $this->faker->randomElement(['credit_card', 'bank_transfer', 'cash', 'check']),
            'tenant_id' => User::factory(),
        ];
    }
}
