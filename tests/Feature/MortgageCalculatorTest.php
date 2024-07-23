<?php

namespace Tests\Feature;

use App\Services\MortgageCalculatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MortgageCalculatorTest extends TestCase
{
    public function test_mortgage_calculation_accuracy()
    {
        $calculatorService = new MortgageCalculatorService();

        $result = $calculatorService->calculateMortgage(
            propertyPrice: 300000,
            loanAmount: 240000,
            interestRate: 3.5,
            loanTerm: 30
        );

        $this->assertEquals(1077.71, round($result['monthly_payment'], 2));
	$this->assertEqualsWithDelta(387975.60, $result['total_payment'], 0.01);
        $this->assertEquals(147975.60, round($result['total_interest'], 2));

        // Test the amortization schedule
        $this->assertCount(360, $result['amortization_schedule']);
    }
}
