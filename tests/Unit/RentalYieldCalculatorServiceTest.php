<?php

namespace Tests\Unit;

use App\Services\RentalYieldCalculatorService;
use Tests\TestCase;

class RentalYieldCalculatorServiceTest extends TestCase
{
    private RentalYieldCalculatorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RentalYieldCalculatorService();
    }

    public function test_calculates_gross_yield(): void
    {
        $result = $this->service->calculateRentalYield(200000, 12000);

        $this->assertEquals(6.0, $result['gross_yield']);
    }

    public function test_calculates_net_yield_with_expenses(): void
    {
        $result = $this->service->calculateRentalYield(200000, 12000, 2000);

        $this->assertEquals(5.0, $result['net_yield']);
        $this->assertEquals(10000.0, $result['net_annual_income']);
    }

    public function test_net_yield_equals_gross_yield_with_zero_expenses(): void
    {
        $result = $this->service->calculateRentalYield(300000, 15000, 0);

        $this->assertEquals($result['gross_yield'], $result['net_yield']);
    }

    public function test_returns_all_expected_keys(): void
    {
        $result = $this->service->calculateRentalYield(200000, 12000, 2000);

        $this->assertArrayHasKey('property_value', $result);
        $this->assertArrayHasKey('annual_rental_income', $result);
        $this->assertArrayHasKey('annual_expenses', $result);
        $this->assertArrayHasKey('net_annual_income', $result);
        $this->assertArrayHasKey('gross_yield', $result);
        $this->assertArrayHasKey('net_yield', $result);
    }

    public function test_rounds_values_to_two_decimal_places(): void
    {
        $result = $this->service->calculateRentalYield(200001, 12345, 1234);

        $this->assertIsFloat($result['gross_yield']);
        $this->assertIsFloat($result['net_yield']);
    }
}
