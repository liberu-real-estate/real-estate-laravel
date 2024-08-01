<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\AccountingInterfaces\AccountingSystemInterface;
use Illuminate\Support\Facades\Log;

class AccountingIntegrationService
{
    protected $accountingSystem;

    public function __construct(AccountingSystemInterface $accountingSystem)
    {
        $this->accountingSystem = $accountingSystem;
    }

    public function syncInvoice(Invoice $invoice)
    {
        try {
            $result = $this->accountingSystem->syncInvoice($invoice);

            if ($result) {
                return true;
            } else {
                Log::error('Accounting sync failed for invoice ' . $invoice->id);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Accounting sync error for invoice ' . $invoice->id . ': ' . $e->getMessage());
            return false;
        }
    }

    public function syncPayment(Payment $payment)
    {
        try {
            $result = $this->accountingSystem->syncPayment($payment);

            if ($result) {
                return true;
            } else {
                Log::error('Accounting sync failed for payment ' . $payment->id);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Accounting sync error for payment ' . $payment->id . ': ' . $e->getMessage());
            return false;
        }
    }
}