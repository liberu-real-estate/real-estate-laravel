<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Transaction;
use App\Models\Property;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use App\Services\TransactionService;

class PaymentController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function showPaymentPortal()
    {
        return view('tenant.payment-portal');
    }

    public function createSession(Request $request)
    {
        $this->validateCreateSessionRequest($request);
        $this->setStripeApiKey();
        $paymentIntent = $this->createPaymentIntent($request->amount, $request->invoice_id);

        return response()->json(['clientSecret' => $paymentIntent->client_secret]);
    }

    public function handlePaymentSuccess(Request $request){
        $this->validateHandlePaymentSuccessRequest($request);

        $payment = Payment::create([
            'amount' => $request->amount,
            'payment_date' => now(),
            'status' => 'completed',
            'payment_method' => $request->payment_method,
            'tenant_id' => Auth::id(),
            'invoice_id' => $request->invoice_id,
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);
        $invoice->update(['status' => 'paid']);

        return response()->json([
            'message' => 'Payment successful and recorded.',
            'payment_id' => $payment->id,
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
            'invoice_id' => 'required|integer',
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
            'invoice_id' => 'required|integer',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
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
     * @param int $invoiceId
     * @return PaymentIntent
     */
    private function createPaymentIntent($amount, $invoiceId)
    {
        return PaymentIntent::create([
            'amount' => $amount * 100, // Convert amount to cents
            'currency' => 'usd',
            'metadata' => ['invoice_id' => $invoiceId],
        ]);
    }

    public function generateReceipt($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        // Generate and return a PDF receipt
        // This is a placeholder and should be implemented with a PDF generation library
        return response()->json(['message' => 'Receipt generated', 'payment' => $payment]);
    }
}
