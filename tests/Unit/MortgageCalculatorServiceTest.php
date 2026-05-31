<?php

namespace Tests\Unit;

use App\Services\MortgageCalculatorService;
use Tests\TestCase;

class MortgageCalculatorServiceTest extends TestCase
{
    private MortgageCalculatorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MortgageCalculatorService();
    }

    public function test_calculates_monthly_payment_correctly(): void
    {
        $result = $this->service->calculateMortgage(200000, 160000, 3.5, 25);

        $this->assertArrayHasKey('monthly_payment', $result);
        $this->assertArrayHasKey('total_payment', $result);
        $this->assertArrayHasKey('total_interest', $result);
        $this->assertArrayHasKey('amortization_schedule', $result);
        $this->assertGreaterThan(0, $result['monthly_payment']);
        $this->assertEquals(
            round($result['total_payment'] - 160000, 2),
            $result['total_interest']
        );
    }

    public function test_calculates_zero_interest_loan(): void
    {
        $result = $this->service->calculateMortgage(100000, 100000, 0, 10);

        $this->assertEquals(round(100000 / 120, 2), $result['monthly_payment']);
        $this->assertEquals(0.0, $result['total_interest']);
    }

    public function test_amortization_schedule_length_matches_term(): void
    {
        $result = $this->service->calculateMortgage(150000, 120000, 4.0, 5);

        $this->assertCount(60, $result['amortization_schedule']);
    }

    public function test_amortization_schedule_has_correct_keys(): void
    {
        $result = $this->service->calculateMortgage(150000, 120000, 4.0, 5);

        $first = $result['amortization_schedule'][0];
        $this->assertArrayHasKey('month', $first);
        $this->assertArrayHasKey('payment', $first);
        $this->assertArrayHasKey('principal', $first);
        $this->assertArrayHasKey('interest', $first);
        $this->assertArrayHasKey('balance', $first);
        $this->assertEquals(1, $first['month']);
    }

    public function test_total_payment_equals_monthly_times_payments(): void
    {
        $result = $this->service->calculateMortgage(200000, 180000, 2.5, 20);

        // Due to rounding at each payment step, total may differ slightly from monthly * n
        $this->assertEqualsWithDelta(
            $result['monthly_payment'] * 240,
            $result['total_payment'],
            5.0
        );
    }
}
