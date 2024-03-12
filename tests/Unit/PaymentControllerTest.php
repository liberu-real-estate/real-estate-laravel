&lt;?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testValidateCreateSessionRequest()
    {
        // Simulate valid and invalid requests and assert outcomes
    }

    public function testSetStripeApiKey()
    {
        // Mock Stripe facade, assert setApiKey is called with correct env variable
    }

    public function testCreatePaymentIntent()
    {
        // Mock Stripe PaymentIntent, simulate valid and invalid creation scenarios, assert outcomes
    }

    public function testValidateHandlePaymentSuccessRequest()
    {
        // Simulate valid and invalid requests and assert outcomes
    }

    public function testCreateAndSaveTransaction()
    {
        // Simulate transaction creation, assert database state and handle failure scenarios
    }
}
