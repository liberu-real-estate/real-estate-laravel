<?php

namespace Tests\Unit;

use App\Services\AccountingSystems\SageOnlineService;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SageOnlineServiceTest extends TestCase
{
    protected $sageOnlineService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sageOnlineService = new SageOnlineService();
    }

    public function test_sync_invoice_success()
    {
        Http::fake([
            '*' => Http::response(['id' => 'sage_invoice_id'], 200),
        ]);

        $invoice = Invoice::factory()->create();
        $result = $this->sageOnlineService->syncInvoice($invoice);

        $this->assertTrue($result);
        $this->assertEquals('sage_invoice_id', $invoice->fresh()->accounting_id);
    }

    public function test_sync_invoice_failure()
    {
        Http::fake([
            '*' => Http::response(null, 500),
        ]);

        $invoice = Invoice::factory()->create();
        $result = $this->sageOnlineService->syncInvoice($invoice);

        $this->assertFalse($result);
        $this->assertNull($invoice->fresh()->accounting_id);
    }

    public function test_sync_payment_success()
    {
        Http::fake([
            '*' => Http::response(['id' => 'sage_payment_id'], 200),
        ]);

        $payment = Payment::factory()->create();
        $result = $this->sageOnlineService->syncPayment($payment);

        $this->assertTrue($result);
        $this->assertEquals('sage_payment_id', $payment->fresh()->accounting_id);
    }

    public function test_sync_payment_failure()
    {
        Http::fake([
            '*' => Http::response(null, 500),
        ]);

        $payment = Payment::factory()->create();
        $result = $this->sageOnlineService->syncPayment($payment);

        $this->assertFalse($result);
        $this->assertNull($payment->fresh()->accounting_id);
    }
}