<?php

namespace Tests\Unit;

use App\Services\AccountingSystems\XeroService;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class XeroServiceTest extends TestCase
{
    protected $xeroService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->xeroService = new XeroService();
    }

    public function test_sync_invoice_success()
    {
        Http::fake([
            '*' => Http::response(['id' => 'xero_invoice_id'], 200),
        ]);

        $invoice = Invoice::factory()->create();
        $result = $this->xeroService->syncInvoice($invoice);

        $this->assertTrue($result);
        $this->assertEquals('xero_invoice_id', $invoice->fresh()->accounting_id);
    }

    public function test_sync_invoice_failure()
    {
        Http::fake([
            '*' => Http::response(null, 500),
        ]);

        $invoice = Invoice::factory()->create();
        $result = $this->xeroService->syncInvoice($invoice);

        $this->assertFalse($result);
        $this->assertNull($invoice->fresh()->accounting_id);
    }

    public function test_sync_payment_success()
    {
        Http::fake([
            '*' => Http::response(['id' => 'xero_payment_id'], 200),
        ]);

        $payment = Payment::factory()->create();
        $result = $this->xeroService->syncPayment($payment);

        $this->assertTrue($result);
        $this->assertEquals('xero_payment_id', $payment->fresh()->accounting_id);
    }

    public function test_sync_payment_failure()
    {
        Http::fake([
            '*' => Http::response(null, 500),
        ]);

        $payment = Payment::factory()->create();
        $result = $this->xeroService->syncPayment($payment);

        $this->assertFalse($result);
        $this->assertNull($payment->fresh()->accounting_id);
    }
}