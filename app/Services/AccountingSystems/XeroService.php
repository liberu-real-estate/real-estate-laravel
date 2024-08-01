<?php

namespace App\Services\AccountingSystems;

use App\Services\AccountingInterfaces\AccountingSystemInterface;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XeroService implements AccountingSystemInterface
{
    protected $apiKey;
    protected $endpoint;

    public function __construct()
    {
        $this->apiKey = config('services.xero.api_key');
        $this->endpoint = config('services.xero.endpoint');
    }

    public function syncInvoice(Invoice $invoice): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->endpoint . '/Invoices', [
                'InvoiceNumber' => $invoice->id,
                'Type' => 'ACCREC',
                'Contact' => [
                    'ContactID' => $invoice->customer_id // Assuming customer_id is stored in the invoice
                ],
                'LineItems' => [
                    [
                        'Description' => $invoice->description,
                        'Quantity' => 1,
                        'UnitAmount' => $invoice->amount,
                        'AccountCode' => '200' // Assuming '200' is the sales account code in Xero
                    ]
                ],
                'Date' => $invoice->created_at->format('Y-m-d'),
                'DueDate' => $invoice->due_date->format('Y-m-d'),
                'Status' => 'AUTHORISED',
            ]);

            if ($response->successful()) {
                $invoice->update(['accounting_id' => $response->json('Invoices.0.InvoiceID')]);
                return true;
            } else {
                Log::error('Xero sync failed for invoice ' . $invoice->id . ': ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Xero sync error for invoice ' . $invoice->id . ': ' . $e->getMessage());
            return false;
        }
    }

    public function syncPayment(Payment $payment): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->endpoint . '/Payments', [
                'Invoice' => [
                    'InvoiceID' => $payment->invoice->accounting_id
                ],
                'Account' => [
                    'Code' => '090' // Assuming '090' is the bank account code in Xero
                ],
                'Amount' => $payment->amount,
                'Date' => $payment->payment_date->format('Y-m-d'),
                'PaymentType' => $this->mapPaymentMethod($payment->payment_method),
            ]);

            if ($response->successful()) {
                $payment->update(['accounting_id' => $response->json('Payments.0.PaymentID')]);
                return true;
            } else {
                Log::error('Xero sync failed for payment ' . $payment->id . ': ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Xero sync error for payment ' . $payment->id . ': ' . $e->getMessage());
            return false;
        }
    }

    private function mapPaymentMethod(string $paymentMethod): string
    {
        $mapping = [
            'credit_card' => 'CREDITCARD',
            'bank_transfer' => 'DIRECTDEBIT',
            'cash' => 'CASH',
            // Add more mappings as needed
        ];

        return $mapping[$paymentMethod] ?? 'OTHERPAYMENT';
    }
}