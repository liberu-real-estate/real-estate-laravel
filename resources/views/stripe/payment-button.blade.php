<form action="{{ route('stripe.process-payment', $payment->id) }}" method="POST">
    @csrf
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Pay with Stripe
    </button>
</form>