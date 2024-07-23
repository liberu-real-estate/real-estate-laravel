  GNU nano 6.2                      tests/Unit/TenantResourceTest.php
<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\PropertyValuationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;

class PropertyValuationServiceTest extends TestCase
{
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
