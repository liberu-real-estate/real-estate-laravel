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

    /**
     * @dataProvider propertyDataProvider
     */
    public function testBasicValuation($propertyData, $expectedMinValue, $expectedMaxValue)
    {
        $property = new Property($propertyData);

        $estimatedValue = $this->valuationService->calculateValuation($property);

        $this->assertGreaterThan($expectedMinValue, $estimatedValue);
        $this->assertLessThan($expectedMaxValue, $estimatedValue);
    }

    public function propertyDataProvider()
    {
        return [
            'London house' => [
                [
                    'price' => 100000,
                    'location' => 'London',
                    'property_type' => 'house',
                    'area_sqft' => 1500,
                    'year_built' => 2000,
                ],
                100000,
                200000
            ],
            'Manchester apartment' => [
                [
                    'price' => 80000,
                    'location' => 'Manchester',
                    'property_type' => 'apartment',
                    'area_sqft' => 800,
                    'year_built' => 2010,
                ],
                80000,
                160000
            ],
            // Add more test cases here
        ];
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