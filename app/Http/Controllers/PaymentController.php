<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Transaction;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use App\Services\TransactionService;

class PaymentController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function createSession(Request $request)
    {
        $this->validateCreateSessionRequest($request);
        $this->setStripeApiKey();
        $paymentIntent = $this->createPaymentIntent($request->amount, $request->property_id);

        return response()->json(['clientSecret' => $paymentIntent->client_secret]);
    }

    public function handlePaymentSuccess(Request $request){
        $this->validateHandlePaymentSuccessRequest($request);

        $transaction = $this->transactionService->createTransaction([
            'property_id' => $request->property_id,
            'buyer_id' => Auth::id(),
            'seller_id' => $this->getSellerIdFromProperty($request->property_id),
            'transaction_date' => now(),
            'transaction_amount' => $request->amount,
            'status' => Transaction::STATUS_COMPLETED,
        ]);

        // Generate contractual document
        $document = $this->transactionService->generateContractualDocument($transaction);

        return response()->json([
            'message' => 'Payment successful and transaction recorded.',
            'transaction_id' => $transaction->id,
            'document_id' => $document->id,
        ]);
    }

    /**
     * Validates the request for the createSession method.
     *
     * @param Request $request
     */
    private function validateCreateSessionRequest(Request $request)
    {
        $request->validate([
            'property_id' => 'required|integer',
            'amount' => 'required|numeric',
        ]);
    }

    /**
     * Validates the request for the handlePaymentSuccess method.
     *
     * @param Request $request
     */
    private function validateHandlePaymentSuccessRequest(Request $request)
    {
        $request->validate([
            'property_id' => 'required|integer',
            'transaction_id' => 'required|string',
            'amount' => 'required|numeric',
        ]);
    }

    /**
     * Sets the Stripe API key.
     */
    private function setStripeApiKey()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    /**
     * Creates a payment intent.
     *
     * @param float $amount
     * @param int $propertyId
     * @return PaymentIntent
     */
    private function createPaymentIntent($amount, $propertyId)
    {
        return PaymentIntent::create([
            'amount' => $amount * 100, // Convert amount to cents
            'currency' => 'gbp',
            'metadata' => ['property_id' => $propertyId],
        ]);
    }

    private function getSellerIdFromProperty($propertyId)
    {
        return Property::findOrFail($propertyId)->seller_id;
    }
}
