<?php

/**
 * PaymentController.php
 *
 * The PaymentController class is responsible for handling payment-related actions within the real estate application.
 * This includes creating payment sessions and processing successful payments.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function createSession(Request $request)
    {
        $this->validateCreateSessionRequest($request);
        $this->setStripeApiKey();
        $paymentIntent = $this->createPaymentIntent($request->amount, $request->property_id);

        return response()->json(['clientSecret' => $paymentIntent->client_secret]);
    }

    /**
     * Handles the successful payment process and records transaction details.
     * 
     * This method validates the payment success request, records the transaction details including property ID, 
     * buyer ID, seller ID, transaction date, and amount in the database, and returns a success message.
     * 
     * @param Request $request Contains the property ID, transaction ID, and amount.
     * @return \
Illuminate\Http\JsonResponse Returns a JSON response with the message of success.
     */
    public function handlePaymentSuccess(Request $request)
            'transaction_id' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $transaction = new Transaction();
        $transaction->property_id = $request->property_id;
        $transaction->buyer_id = Auth::id();
        $transaction->seller_id = $this->getSellerIdFromProperty($request->property_id);
        $transaction->transaction_date = now();
        $transaction->transaction_amount = $request->amount;
        $transaction->save();

        return response()->json(['message' => 'Payment successful and transaction recorded.']);
    }

    private function getSellerIdFromProperty($propertyId)
    {
            'property_id' => 'required|integer',
            'amount' => 'required|numeric',
        ]);
    }

    /**
     * Validates the request for the handlePaymentSuccess method.
     *
     * @param Request $request
     */
    /**
     * Creates a payment session for a property purchase.
     * 
     * This method validates the session creation request, sets the Stripe API key,
     * creates a payment intent with the specified amount and property ID, and returns
     * a response containing the client secret for the payment intent.
     * 
     * @param Request $request Contains the amount and property ID for the payment session.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with the client secret.
     */
    /**
     * Handles the successful payment process.
     * 
     * This method validates the payment success request, creates a new transaction record
     * with the property ID, buyer ID, seller ID, transaction date, and amount, and saves
     * it to the database. It then returns a JSON response indicating the payment was successful
     * and the transaction was recorded.
     * 
     * @param Request $request Contains the property ID, transaction ID, and amount.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response indicating success.
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
            'currency' => 'usd',
            'metadata' => ['property_id' => $propertyId],
        ]);
    }

    /**
     * Creates and saves a transaction.
     *
     * @param int $propertyId
     * @param int $buyerId
     * @param int $sellerId
     * @param \DateTime $transactionDate
     * @param float $transactionAmount
     */
    private function createAndSaveTransaction($propertyId, $buyerId, $sellerId, $transactionDate, $transactionAmount)
    {
        $transaction = new Transaction();
        $transaction->property_id = $propertyId;
        $transaction->buyer_id = $buyerId;
        $transaction->seller_id = $sellerId;
        $transaction->transaction_date = $transactionDate;
        $transaction->transaction_amount = $transactionAmount;
        $transaction->save();
    }

    private function getSellerIdFromProperty($propertyId)
    {
        // Assuming there's a method to fetch the seller's ID based on the property ID.
        // This is a placeholder for the actual implementation.
        return Property::find($propertyId)->seller_id;
    }
}
