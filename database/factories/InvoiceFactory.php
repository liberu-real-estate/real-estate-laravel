<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'tenant_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'due_date' => $this->faker->dateTimeBetween('now', '+3 months'),
            'status' => $this->faker->randomElement(['pending', 'paid', 'overdue', 'cancelled']),
            'description' => $this->faker->sentence(),
        ];
    }
}
