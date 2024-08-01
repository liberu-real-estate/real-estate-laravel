<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\AccountingInterfaces\AccountingSystemInterface;
use App\Services\AccountingSystems\QuickbooksService;
use App\Services\AccountingSystems\SageOnlineService;
use App\Services\AccountingSystems\XeroService;
use Illuminate\Support\Facades\Log;

class AccountingIntegrationService
{
    protected $accountingSystem;

    public function __construct()
    {
        $this->accountingSystem = $this->getAccountingSystem();
    }

    protected function getAccountingSystem(): AccountingSystemInterface
    {
        $system = config('services.accounting.system');

        switch ($system) {
            case 'quickbooks':
                return new QuickbooksService();
            case 'sage':
                return new SageOnlineService();
            case 'xero':
                return new XeroService();
            default:
                throw new \Exception("Unsupported accounting system: {$system}");
        }
    }

    public function syncInvoice(Invoice $invoice)
    {
        try {
            return $this->accountingSystem->syncInvoice($invoice);
        } catch (\Exception $e) {
            Log::error('Accounting sync error for invoice ' . $invoice->id . ': ' . $e->getMessage());
            return false;
        }
    }

    public function syncPayment(Payment $payment)
    {
        try {
            return $this->accountingSystem->syncPayment($payment);
        } catch (\Exception $e) {
            Log::error('Accounting sync error for payment ' . $payment->id . ': ' . $e->getMessage());
            return false;
        }
    }
}