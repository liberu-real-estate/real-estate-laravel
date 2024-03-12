<?php

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
        $request->validate([
            'property_id' => 'required|integer',
            'amount' => 'required|numeric',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount * 100, // Convert amount to cents
            'currency' => 'usd',
            'metadata' => ['property_id' => $request->property_id],
        ]);

        return response()->json(['clientSecret' => $paymentIntent->client_secret]);
    }

    public function handlePaymentSuccess(Request $request)
    {
        $request->validate([
            'property_id' => 'required|integer',
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
        // Assuming there's a method to fetch the seller's ID based on the property ID.
        // This is a placeholder for the actual implementation.
        return Property::find($propertyId)->seller_id;
    }
}
