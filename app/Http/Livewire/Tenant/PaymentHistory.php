<?php

namespace App\Http\Livewire\Tenant;

use Livewire\Component;
use App\Models\Payment;

class PaymentHistory extends Component
{
    public function render()
    {
        $payments = Payment::where('tenant_id', auth()->id())
            ->orderBy('payment_date', 'desc')
            ->paginate(10);

        return view('livewire.tenant.payment-history', [
            'payments' => $payments,
        ]);
    }
}