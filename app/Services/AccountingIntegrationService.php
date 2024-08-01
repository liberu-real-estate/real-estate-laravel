<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\AccountingInterfaces\AccountingSystemInterface;
use App\Services\AccountingSystems\QuickbooksService;
use App\Services\AccountingSystems\SageService;
use App\Services\AccountingSystems\XeroService;

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
                return new SageService();
            case 'xero':
                return new XeroService();
            default:
                throw new \Exception("Unsupported accounting system: $system");
        }
    }

    public function syncInvoice(Invoice $invoice): bool
    {
        return $this->accountingSystem->syncInvoice($invoice);
    }

    public function syncPayment(Payment $payment): bool
    {
        return $this->accountingSystem->syncPayment($payment);
    }
}