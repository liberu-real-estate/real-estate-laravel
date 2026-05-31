<?php

namespace Tests\Unit;

use App\Services\CostOfMovingCalculatorService;
use Tests\TestCase;

class CostOfMovingCalculatorServiceTest extends TestCase
{
    private CostOfMovingCalculatorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CostOfMovingCalculatorService();
    }

    public function test_returns_all_cost_components(): void
    {
        $result = $this->service->calculateCostOfMoving(250000, false, 100);

        $this->assertArrayHasKey('estate_agent_fee', $result);
        $this->assertArrayHasKey('conveyancing_fee', $result);
        $this->assertArrayHasKey('survey_fee', $result);
        $this->assertArrayHasKey('removals', $result);
        $this->assertArrayHasKey('energy_performance_certificate', $result);
        $this->assertArrayHasKey('total_cost', $result);
    }

    public function test_first_time_buyer_has_lower_conveyancing_fee(): void
    {
        $ftb = $this->service->calculateCostOfMoving(200000, true, 50);
        $non_ftb = $this->service->calculateCostOfMoving(200000, false, 50);

        $this->assertLessThan($non_ftb['conveyancing_fee'], $ftb['conveyancing_fee']);
    }

    public function test_estate_agent_fee_is_15_percent(): void
    {
        $result = $this->service->calculateCostOfMoving(200000, false, 0);

        $this->assertEquals(3000.0, $result['estate_agent_fee']);
    }

    public function test_survey_fee_tiers(): void
    {
        $cheap = $this->service->calculateCostOfMoving(80000, false, 0);
        $mid = $this->service->calculateCostOfMoving(150000, false, 0);
        $expensive = $this->service->calculateCostOfMoving(300000, false, 0);

        $this->assertEquals(300, $cheap['survey_fee']);
        $this->assertEquals(400, $mid['survey_fee']);
        $this->assertEquals(500, $expensive['survey_fee']);
    }

    public function test_total_is_sum_of_components(): void
    {
        $result = $this->service->calculateCostOfMoving(200000, false, 100);

        $expected = $result['estate_agent_fee']
            + $result['conveyancing_fee']
            + $result['survey_fee']
            + $result['removals']
            + $result['energy_performance_certificate'];

        $this->assertEqualsWithDelta($expected, $result['total_cost'], 0.01);
    }

    public function test_epc_is_fixed_at_120(): void
    {
        $result = $this->service->calculateCostOfMoving(500000, true, 200);

        $this->assertEquals(120, $result['energy_performance_certificate']);
    }
}
