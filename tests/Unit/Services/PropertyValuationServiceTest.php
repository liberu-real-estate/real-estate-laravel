<?php

namespace Tests\Unit\Services;

use App\Models\Property;
use App\Services\PropertyValuationService;
use PHPUnit\Framework\TestCase;

class PropertyValuationServiceTest extends TestCase
{
    protected PropertyValuationService $valuationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->valuationService = new PropertyValuationService();
    }

    public function testBasicValuation()
    {
        $property = new Property([
            'price' => 100000,
            'location' => 'London',
            'property_type' => 'house',
            'area_sqft' => 1500,
            'year_built' => 2000,
        ]);

        $estimatedValue = $this->valuationService->calculateValuation($property);

        $this->assertGreaterThan($property->price, $estimatedValue);
        $this->assertLessThan($property->price * 2, $estimatedValue);
    }

    public function testValuationWithMarketTrend()
    {
        $property = new Property([
            'price' => 100000,
            'location' => 'Manchester',
            'property_type' => 'apartment',
            'area_sqft' => 800,
            'year_built' => 2010,
        ]);

        $estimatedValue1 = $this->valuationService->calculateValuation($property);
        $estimatedValue2 = $this->valuationService->calculateValuation($property, ['market_trend_factor' => 1.1]);

        $this->assertGreaterThan($estimatedValue1, $estimatedValue2);
    }

    // Add more test cases to cover different scenarios and edge cases
}