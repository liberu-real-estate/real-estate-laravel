<?php

namespace Tests\Unit;

use App\Models\Transaction;
use App\Models\Property;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_transaction()
    {
        $transactionData = [
            'amount' => 250000,
            'type' => 'sale',
            'status' => 'completed',
            'date' => now(),
        ];

        $transaction = Transaction::create($transactionData);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertDatabaseHas('transactions', $transactionData);
    }

    public function test_transaction_relationships()
    {
        $transaction = Transaction::factory()->create();

        $this->assertInstanceOf(Property::class, $transaction->property);
        $this->assertInstanceOf(User::class, $transaction->buyer);
        $this->assertInstanceOf(User::class, $transaction->seller);
    }

    public function test_transaction_scopes()
    {
        Transaction::factory()->count(3)->create(['type' => 'sale']);
        Transaction::factory()->count(2)->create(['type' => 'rental']);

        $this->assertCount(3, Transaction::sales()->get());
        $this->assertCount(2, Transaction::rentals()->get());
    }
}