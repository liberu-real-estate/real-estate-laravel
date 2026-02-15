<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Services\AIInvestmentAnalysisService;
use App\Services\MarketAnalysisService;
use App\Services\PropertyRecommendationService;
use App\Services\PropertyValuationService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvestmentAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_investment_analysis_returns_valid_structure()
    {
        // Create a test property
        $property = Property::factory()->create([
            'title' => 'Investment Test Property',
            'price' => 300000,
            'property_type' => 'House',
            'location' => 'London',
            'year_built' => 2015,
        ]);

        // Mock the required services
        $marketAnalysisService = $this->createMock(MarketAnalysisService::class);
        $propertyRecommendationService = $this->createMock(PropertyRecommendationService::class);
        $propertyValuationService = $this->createMock(PropertyValuationService::class);

        // Set up mock expectations
        $marketAnalysisService->method('generateMarketAnalysis')
            ->willReturn([
                'market_data' => collect([
                    (object)[
                        'property_type' => 'House',
                        'avg_price' => 320000,
                        'total_properties' => 10,
                    ]
                ]),
                'price_per_sqft' => collect([]),
            ]);

        $propertyValuationService->method('calculateValuation')
            ->willReturn(300000);

        $propertyRecommendationService->method('getRecommendations')
            ->willReturn([]);

        // Create service instance with mocked dependencies
        $service = new AIInvestmentAnalysisService(
            $marketAnalysisService,
            $propertyRecommendationService,
            $propertyValuationService
        );

        // Run the analysis
        $result = $service->analyzeInvestment($property);

        // Assert the structure is correct
        $this->assertArrayHasKey('market_analysis', $result);
        $this->assertArrayHasKey('valuation', $result);
        $this->assertArrayHasKey('recommendations', $result);
        $this->assertArrayHasKey('prediction', $result);
        $this->assertArrayHasKey('cash_flow_analysis', $result);
        $this->assertArrayHasKey('market_position', $result);

        // Assert prediction structure
        $this->assertArrayHasKey('predicted_roi', $result['prediction']);
        $this->assertArrayHasKey('risk_score', $result['prediction']);

        // Assert cash flow analysis structure
        $this->assertArrayHasKey('estimated_annual_rent', $result['cash_flow_analysis']);
        $this->assertArrayHasKey('estimated_expenses', $result['cash_flow_analysis']);
        $this->assertArrayHasKey('net_cash_flow', $result['cash_flow_analysis']);
        $this->assertArrayHasKey('cash_on_cash_return', $result['cash_flow_analysis']);

        // Assert market position structure
        $this->assertArrayHasKey('position', $result['market_position']);
        $this->assertArrayHasKey('price_vs_market', $result['market_position']);
        $this->assertArrayHasKey('competitive_advantage', $result['market_position']);
    }

    public function test_investment_analysis_handles_empty_market_data()
    {
        // Create a test property
        $property = Property::factory()->create([
            'price' => 250000,
            'property_type' => 'Apartment',
            'location' => 'Manchester',
            'year_built' => 2010,
        ]);

        // Mock the required services with empty market data
        $marketAnalysisService = $this->createMock(MarketAnalysisService::class);
        $propertyRecommendationService = $this->createMock(PropertyRecommendationService::class);
        $propertyValuationService = $this->createMock(PropertyValuationService::class);

        $marketAnalysisService->method('generateMarketAnalysis')
            ->willReturn([
                'market_data' => collect([]),
                'price_per_sqft' => collect([]),
            ]);

        $propertyValuationService->method('calculateValuation')
            ->willReturn(250000);

        $propertyRecommendationService->method('getRecommendations')
            ->willReturn([]);

        $service = new AIInvestmentAnalysisService(
            $marketAnalysisService,
            $propertyRecommendationService,
            $propertyValuationService
        );

        $result = $service->analyzeInvestment($property);

        // Should still return valid data with defaults
        $this->assertIsArray($result);
        $this->assertArrayHasKey('prediction', $result);
        $this->assertArrayHasKey('cash_flow_analysis', $result);
        $this->assertEquals('average', $result['market_position']['position']);
    }

    public function test_cash_flow_analysis_calculations()
    {
        $property = Property::factory()->create([
            'price' => 200000,
            'property_type' => 'House',
            'year_built' => 2018,
        ]);

        $marketAnalysisService = $this->createMock(MarketAnalysisService::class);
        $propertyRecommendationService = $this->createMock(PropertyRecommendationService::class);
        $propertyValuationService = $this->createMock(PropertyValuationService::class);

        $marketAnalysisService->method('generateMarketAnalysis')
            ->willReturn([
                'market_data' => collect([]),
                'price_per_sqft' => collect([]),
            ]);

        $propertyValuationService->method('calculateValuation')
            ->willReturn(200000);

        $propertyRecommendationService->method('getRecommendations')
            ->willReturn([]);

        $service = new AIInvestmentAnalysisService(
            $marketAnalysisService,
            $propertyRecommendationService,
            $propertyValuationService
        );

        $result = $service->analyzeInvestment($property);

        // Verify cash flow calculations
        $cashFlow = $result['cash_flow_analysis'];
        $expectedRent = 200000 * 0.05; // 5% yield
        $expectedExpenses = $expectedRent * 0.30; // 30% expenses
        $expectedNetCashFlow = $expectedRent - $expectedExpenses;

        $this->assertEquals(round($expectedRent, 2), $cashFlow['estimated_annual_rent']);
        $this->assertEquals(round($expectedExpenses, 2), $cashFlow['estimated_expenses']);
        $this->assertEquals(round($expectedNetCashFlow, 2), $cashFlow['net_cash_flow']);
    }
}
