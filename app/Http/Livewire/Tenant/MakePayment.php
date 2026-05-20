<?php

namespace App\Http\Livewire\Tenant;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class MakePayment extends Component
{
    public $amount;
    public $paymentMethod;
    public $invoiceId;

    protected $rules = [
        'amount' => 'required|numeric|min:0.01',
        'paymentMethod' => 'required|in:credit_card,bank_transfer',
        'invoiceId' => 'required|exists:invoices,id',
    ];

    public function mount()
    {
        $this->invoiceId = Invoice::where('tenant_id', auth()->id())
            ->where('status', 'unpaid')
            ->first()->id ?? null;
    }

    public function createPaymentIntent()
    {
        $this->validate();

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $this->amount * 100,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
        ]);

        return $paymentIntent->client_secret;
    }

    public function processPayment()
    {
        $this->validate();

        // Process payment logic here
        // For demonstration, we'll just create a new payment record
        Payment::create([
            'amount' => $this->amount,
            'payment_date' => now(),
            'status' => 'completed',
            'payment_method' => $this->paymentMethod,
            'tenant_id' => auth()->id(),
            'invoice_id' => $this->invoiceId,
        ]);

        $this->emit('paymentProcessed');
        session()->flash('message', 'Payment processed successfully.');
    }

    public function render()
    {
        return view('livewire.tenant.make-payment');
    }
}