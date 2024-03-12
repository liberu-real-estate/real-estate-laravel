<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\PaymentController;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Http;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCreateSessionValidation()
    {
        $response = $this->json('POST', '/payment/create-session', []);
        $response->assertStatus(422)->assertJsonValidationErrors(['property_id', 'amount']);

        $response = $this->json('POST', '/payment/create-session', ['property_id' => 'not-an-integer', 'amount' => 'not-a-number']);
        $response->assertStatus(422)->assertJsonValidationErrors(['property_id', 'amount']);
    }

    public function testCreateSessionSuccess()
    {
        Http::fake([
            'api.stripe.com/*' => Http::response(['client_secret' => 'some_client_secret'], 200),
        ]);

        $response = $this->json('POST', '/payment/create-session', ['property_id' => 1, 'amount' => 100.00]);
        $response->assertStatus(200)->assertJson(['clientSecret' => 'some_client_secret']);
    }

    public function testHandlePaymentSuccessValidation()
    {
        $response = $this->json('POST', '/payment/handle-success', []);
        $response->assertStatus(422)->assertJsonValidationErrors(['property_id', 'transaction_id', 'amount']);

        $response = $this->json('POST', '/payment/handle-success', ['property_id' => 'not-an-integer', 'transaction_id' => 123, 'amount' => 'not-a-number']);
        $response->assertStatus(422)->assertJsonValidationErrors(['property_id', 'amount']);
    }

    public function testHandlePaymentSuccess()
    {
        Auth::shouldReceive('id')->once()->andReturn(1);

        $response = $this->json('POST', '/payment/handle-success', ['property_id' => 1, 'transaction_id' => 'txn_123456789', 'amount' => 100.00]);
        $response->assertStatus(200)->assertJson(['message' => 'Payment successful and transaction recorded.']);

        $this->assertDatabaseHas('transactions', [
            'property_id' => 1,
            'buyer_id' => 1,
            'transaction_amount' => 100.00,
        ]);
    }
}
