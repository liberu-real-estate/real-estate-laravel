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
        $property = Property::factory()->create();
        $user = User::factory()->create();

        $transactionData = [
            'property_id' => $property->id,
            'user_id' => $user->id,
            'amount' => 100000,
            'type' => 'sale',
            'status' => 'completed',
            'date' => now(),
        ];

        $transaction = Transaction::create($transactionData);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertDatabaseHas('transactions', ['id' => $transaction->id]);
    }

    public function test_transaction_relationships()
    {
        $transaction = Transaction::factory()->create();

        $this->assertInstanceOf(Property::class, $transaction->property);
        $this->assertInstanceOf(User::class, $transaction->user);
    }

    public function test_transaction_scope()
    {
        $completedTransaction = Transaction::factory()->create(['status' => 'completed']);
        $pendingTransaction = Transaction::factory()->create(['status' => 'pending']);

        $completedTransactions = Transaction::completed()->get();
        $pendingTransactions = Transaction::pending()->get();

        $this->assertCount(1, $completedTransactions);
        $this->assertCount(1, $pendingTransactions);
        $this->assertEquals($completedTransaction->id, $completedTransactions->first()->id);
        $this->assertEquals($pendingTransaction->id, $pendingTransactions->first()->id);
    }

    public function test_transaction_amount_is_numeric()
    {
        $transaction = Transaction::factory()->create(['amount' => 100000]);
        $this->assertIsNumeric($transaction->amount);
    }

    public function test_transaction_date_is_date_time()
    {
        $transaction = Transaction::factory()->create(['date' => now()]);
        $this->assertInstanceOf(\DateTime::class, $transaction->date);
    }
}