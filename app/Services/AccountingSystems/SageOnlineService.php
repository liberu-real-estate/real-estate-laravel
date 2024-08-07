<?php

namespace App\Services\AccountingSystems;

use App\Services\AccountingInterfaces\AccountingSystemInterface;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SageOnlineService implements AccountingSystemInterface
{
    protected $apiKey;
    protected $endpoint;

    public function __construct()
    {
        $this->apiKey = config('services.sage_online.api_key');
        $this->endpoint = config('services.sage_online.endpoint');
    }

    public function syncInvoice(Invoice $invoice): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->endpoint . '/invoices', [
                'invoice_number' => $invoice->id,
                'amount' => $invoice->amount,
                'due_date' => $invoice->due_date->format('Y-m-d'),
                'description' => $invoice->description,
                'status' => $invoice->status,
            ]);

            if ($response->successful()) {
                $invoice->update(['accounting_id' => $response->json('id')]);
                return true;
            } else {
                Log::error('Sage Online sync failed for invoice ' . $invoice->id . ': ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Sage Online sync error for invoice ' . $invoice->id . ': ' . $e->getMessage());
            return false;
        }
    }

    public function syncPayment(Payment $payment): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->endpoint . '/payments', [
                'payment_id' => $payment->id,
                'invoice_id' => $payment->invoice_id,
                'amount' => $payment->amount,
                'payment_date' => $payment->payment_date->format('Y-m-d'),
                'payment_method' => $payment->payment_method,
            ]);

            if ($response->successful()) {
                $payment->update(['accounting_id' => $response->json('id')]);
                return true;
            } else {
                Log::error('Sage Online sync failed for payment ' . $payment->id . ': ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Sage Online sync error for payment ' . $payment->id . ': ' . $e->getMessage());
            return false;
        }
    }
}