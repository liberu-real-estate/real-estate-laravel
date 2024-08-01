<?php

namespace App\Services\AccountingInterfaces;

use App\Models\Invoice;
use App\Models\Payment;

interface AccountingSystemInterface
{
    public function syncInvoice(Invoice $invoice): bool;
    public function syncPayment(Payment $payment): bool;
}