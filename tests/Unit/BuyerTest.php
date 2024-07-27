<?php

namespace Tests\Unit;

use App\Models\Buyer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BuyerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_buyer()
    {
        $buyerData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
        ];

        $buyer = Buyer::create($buyerData);

        $this->assertInstanceOf(Buyer::class, $buyer);
        $this->assertDatabaseHas('buyers', $buyerData);
    }

    public function test_buyer_relationships()
    {
        $buyer = Buyer::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $buyer->properties);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $buyer->appointments);
    }

    public function test_buyer_full_name_attribute()
    {
        $buyer = Buyer::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('John Doe', $buyer->full_name);
    }
}