<?php

namespace Tests\Unit;

use App\Services\StampDutyCalculatorService;
use Tests\TestCase;

class LBTTCalculatorServiceTest extends TestCase
{
    protected StampDutyCalculatorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new StampDutyCalculatorService();
    }

    public function test_calculates_zero_lbtt_for_home_mover_under_145k()
    {
        $result = $this->service->calculateLBTT(100000, 'home_mover');

        $this->assertEquals(0, $result['lbtt']);
        $this->assertEquals(0, $result['effective_tax_rate']);
    }

    public function test_calculates_lbtt_for_home_mover_at_200k()
    {
        $result = $this->service->calculateLBTT(200000, 'home_mover');

        // 0% on first £145k, 2% on £55k = £1,100
        $this->assertEquals(1100, $result['lbtt']);
    }

    public function test_calculates_lbtt_for_home_mover_at_280k()
    {
        $result = $this->service->calculateLBTT(280000, 'home_mover');

        // 0% on first £145k, 2% on £105k = £2,100, 5% on £30k = £1,500 => Total £3,600
        $this->assertEquals(3600, $result['lbtt']);
    }

    public function test_calculates_lbtt_for_first_time_buyer_under_175k()
    {
        $result = $this->service->calculateLBTT(150000, 'first_time_buyer');

        $this->assertEquals(0, $result['lbtt']);
    }

    public function test_calculates_lbtt_for_first_time_buyer_at_200k()
    {
        $result = $this->service->calculateLBTT(200000, 'first_time_buyer');

        // 0% on first £175k, 5% on £25k = £1,250
        $this->assertEquals(1250, $result['lbtt']);
    }

    public function test_calculates_lbtt_for_additional_property()
    {
        $result = $this->service->calculateLBTT(200000, 'additional_property');

        // 6% on first £145k = £8,700, 8% on £55k = £4,400 => Total £13,100
        $this->assertEquals(13100, $result['lbtt']);
    }

    public function test_throws_exception_for_invalid_buyer_type_in_lbtt()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid buyer type');

        $this->service->calculateLBTT(200000, 'invalid_buyer');
    }

    public function test_calculates_ltt_for_home_mover_under_225k()
    {
        $result = $this->service->calculateLTT(200000, 'home_mover');

        $this->assertEquals(0, $result['ltt']);
    }

    public function test_calculates_ltt_for_home_mover_at_300k()
    {
        $result = $this->service->calculateLTT(300000, 'home_mover');

        // 0% on first £225k, 6% on £75k = £4,500
        $this->assertEquals(4500, $result['ltt']);
    }

    public function test_calculates_ltt_for_additional_property()
    {
        $result = $this->service->calculateLTT(200000, 'additional_property');

        // 4% on first £225k but price is only £200k so 4% on £200k = £8,000
        $this->assertEquals(8000, $result['ltt']);
    }

    public function test_returns_effective_tax_rate_for_lbtt()
    {
        $result = $this->service->calculateLBTT(200000, 'home_mover');

        $this->assertArrayHasKey('effective_tax_rate', $result);
        $this->assertEquals(0.55, $result['effective_tax_rate']); // 1100 / 200000 * 100
    }

    public function test_handles_zero_purchase_price_for_lbtt()
    {
        $result = $this->service->calculateLBTT(0, 'home_mover');

        $this->assertEquals(0, $result['lbtt']);
        $this->assertEquals(0, $result['effective_tax_rate']);
    }
}
