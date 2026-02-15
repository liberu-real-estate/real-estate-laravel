<?php

namespace Tests\Unit;

use App\Services\HomeValuationService;
use Tests\TestCase;

class HomeValuationServiceTest extends TestCase
{
    protected HomeValuationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new HomeValuationService();
    }

    /** @test */
    public function it_calculates_basic_home_valuation()
    {
        $result = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 3,
            bathrooms: 2,
            yearBuilt: 2010,
            propertyType: 'terraced',
            condition: 'good',
            location: 'average',
            basePrice: 3000
        );

        $this->assertIsArray($result);
        $this->assertArrayHasKey('estimated_value', $result);
        $this->assertArrayHasKey('min_value', $result);
        $this->assertArrayHasKey('max_value', $result);
        $this->assertArrayHasKey('confidence_level', $result);
        $this->assertGreaterThan(0, $result['estimated_value']);
    }

    /** @test */
    public function it_applies_detached_property_premium()
    {
        $detachedResult = $this->service->calculateHomeValuation(
            propertySize: 2000,
            bedrooms: 4,
            bathrooms: 2,
            yearBuilt: 2015,
            propertyType: 'detached',
            condition: 'good',
            location: 'average',
            basePrice: 3000
        );

        $terracedResult = $this->service->calculateHomeValuation(
            propertySize: 2000,
            bedrooms: 4,
            bathrooms: 2,
            yearBuilt: 2015,
            propertyType: 'terraced',
            condition: 'good',
            location: 'average',
            basePrice: 3000
        );

        $this->assertGreaterThan($terracedResult['estimated_value'], $detachedResult['estimated_value']);
        $this->assertEquals(1.3, $detachedResult['breakdown']['type_multiplier']);
        $this->assertEquals(1.0, $terracedResult['breakdown']['type_multiplier']);
    }

    /** @test */
    public function it_applies_condition_multiplier_correctly()
    {
        $excellentResult = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 3,
            bathrooms: 2,
            yearBuilt: 2010,
            propertyType: 'terraced',
            condition: 'excellent',
            location: 'average',
            basePrice: 3000
        );

        $poorResult = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 3,
            bathrooms: 2,
            yearBuilt: 2010,
            propertyType: 'terraced',
            condition: 'poor',
            location: 'average',
            basePrice: 3000
        );

        $this->assertGreaterThan($poorResult['estimated_value'], $excellentResult['estimated_value']);
        $this->assertEquals(1.2, $excellentResult['breakdown']['condition_multiplier']);
        $this->assertEquals(0.85, $poorResult['breakdown']['condition_multiplier']);
    }

    /** @test */
    public function it_applies_location_multiplier_correctly()
    {
        $primeResult = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 3,
            bathrooms: 2,
            yearBuilt: 2010,
            propertyType: 'terraced',
            condition: 'good',
            location: 'prime',
            basePrice: 3000
        );

        $belowAverageResult = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 3,
            bathrooms: 2,
            yearBuilt: 2010,
            propertyType: 'terraced',
            condition: 'good',
            location: 'below-average',
            basePrice: 3000
        );

        $this->assertGreaterThan($belowAverageResult['estimated_value'], $primeResult['estimated_value']);
        $this->assertEquals(1.4, $primeResult['breakdown']['location_multiplier']);
        $this->assertEquals(0.8, $belowAverageResult['breakdown']['location_multiplier']);
    }

    /** @test */
    public function it_applies_new_build_premium()
    {
        $newBuildResult = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 3,
            bathrooms: 2,
            yearBuilt: date('Y') - 3,
            propertyType: 'terraced',
            condition: 'excellent',
            location: 'average',
            basePrice: 3000
        );

        $olderResult = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 3,
            bathrooms: 2,
            yearBuilt: date('Y') - 40,
            propertyType: 'terraced',
            condition: 'good',
            location: 'average',
            basePrice: 3000
        );

        $this->assertEquals(1.1, $newBuildResult['breakdown']['age_adjustment']);
        $this->assertEquals(0.95, $olderResult['breakdown']['age_adjustment']);
    }

    /** @test */
    public function it_adds_bedroom_bonus_correctly()
    {
        $twoBedroomResult = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 2,
            bathrooms: 1,
            yearBuilt: 2010,
            propertyType: 'terraced',
            condition: 'good',
            location: 'average',
            basePrice: 3000
        );

        $fourBedroomResult = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 4,
            bathrooms: 1,
            yearBuilt: 2010,
            propertyType: 'terraced',
            condition: 'good',
            location: 'average',
            basePrice: 3000
        );

        // 2 extra bedrooms @ £15k each = £30k bonus
        $this->assertEquals(0, $twoBedroomResult['breakdown']['room_bonus']);
        $this->assertEquals(30000, $fourBedroomResult['breakdown']['room_bonus']);
    }

    /** @test */
    public function it_adds_bathroom_bonus_correctly()
    {
        $oneBathroomResult = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 3,
            bathrooms: 1,
            yearBuilt: 2010,
            propertyType: 'terraced',
            condition: 'good',
            location: 'average',
            basePrice: 3000
        );

        $threeBathroomResult = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 3,
            bathrooms: 3,
            yearBuilt: 2010,
            propertyType: 'terraced',
            condition: 'good',
            location: 'average',
            basePrice: 3000
        );

        // 2 extra bathrooms @ £8k each = £16k bonus
        $expectedBonus = 2 * 8000 + (3-2) * 15000; // bathrooms + bedrooms
        $this->assertEquals(15000, $oneBathroomResult['breakdown']['room_bonus']); // 1 bedroom bonus
        $this->assertEquals($expectedBonus, $threeBathroomResult['breakdown']['room_bonus']);
    }

    /** @test */
    public function it_calculates_value_range_correctly()
    {
        $result = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 3,
            bathrooms: 2,
            yearBuilt: 2010,
            propertyType: 'terraced',
            condition: 'good',
            location: 'average',
            basePrice: 3000
        );

        $expectedRange = $result['estimated_value'] * 0.10;
        $expectedMin = $result['estimated_value'] - $expectedRange;
        $expectedMax = $result['estimated_value'] + $expectedRange;

        $this->assertEquals(round($expectedMin, 2), $result['min_value']);
        $this->assertEquals(round($expectedMax, 2), $result['max_value']);
    }

    /** @test */
    public function it_calculates_confidence_level_within_range()
    {
        $result = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 3,
            bathrooms: 2,
            yearBuilt: 2010,
            propertyType: 'terraced',
            condition: 'good',
            location: 'average',
            basePrice: 3000
        );

        $this->assertGreaterThanOrEqual(70, $result['confidence_level']);
        $this->assertLessThanOrEqual(95, $result['confidence_level']);
    }

    /** @test */
    public function it_has_higher_confidence_for_apartments()
    {
        $apartmentResult = $this->service->calculateHomeValuation(
            propertySize: 1000,
            bedrooms: 2,
            bathrooms: 1,
            yearBuilt: 2015,
            propertyType: 'apartment',
            condition: 'excellent',
            location: 'prime',
            basePrice: 3000
        );

        $detachedResult = $this->service->calculateHomeValuation(
            propertySize: 1000,
            bedrooms: 2,
            bathrooms: 1,
            yearBuilt: 2015,
            propertyType: 'detached',
            condition: 'excellent',
            location: 'prime',
            basePrice: 3000
        );

        // Apartments should have slightly higher confidence due to standardization
        $this->assertGreaterThanOrEqual($detachedResult['confidence_level'], $apartmentResult['confidence_level']);
    }

    /** @test */
    public function it_includes_property_details_in_result()
    {
        $result = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 3,
            bathrooms: 2,
            yearBuilt: 2010,
            propertyType: 'terraced',
            condition: 'good',
            location: 'average',
            basePrice: 3000
        );

        $this->assertEquals(1500, $result['property_size']);
        $this->assertEquals(3, $result['bedrooms']);
        $this->assertEquals(2, $result['bathrooms']);
        $this->assertEquals(2010, $result['year_built']);
        $this->assertEquals('terraced', $result['property_type']);
        $this->assertEquals('good', $result['condition']);
        $this->assertEquals('average', $result['location']);
        $this->assertEquals(3000, $result['base_price_per_unit']);
    }

    /** @test */
    public function it_calculates_property_age_correctly()
    {
        $yearBuilt = 2010;
        $result = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 3,
            bathrooms: 2,
            yearBuilt: $yearBuilt,
            propertyType: 'terraced',
            condition: 'good',
            location: 'average',
            basePrice: 3000
        );

        $expectedAge = date('Y') - $yearBuilt;
        $this->assertEquals($expectedAge, $result['property_age']);
    }

    /** @test */
    public function it_includes_detailed_breakdown()
    {
        $result = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 3,
            bathrooms: 2,
            yearBuilt: 2010,
            propertyType: 'terraced',
            condition: 'good',
            location: 'average',
            basePrice: 3000
        );

        $this->assertArrayHasKey('breakdown', $result);
        $this->assertArrayHasKey('base_value', $result['breakdown']);
        $this->assertArrayHasKey('type_multiplier', $result['breakdown']);
        $this->assertArrayHasKey('condition_multiplier', $result['breakdown']);
        $this->assertArrayHasKey('location_multiplier', $result['breakdown']);
        $this->assertArrayHasKey('age_adjustment', $result['breakdown']);
        $this->assertArrayHasKey('room_bonus', $result['breakdown']);
    }

    /** @test */
    public function it_handles_different_base_prices()
    {
        $lowPriceResult = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 3,
            bathrooms: 2,
            yearBuilt: 2010,
            propertyType: 'terraced',
            condition: 'good',
            location: 'average',
            basePrice: 2000
        );

        $highPriceResult = $this->service->calculateHomeValuation(
            propertySize: 1500,
            bedrooms: 3,
            bathrooms: 2,
            yearBuilt: 2010,
            propertyType: 'terraced',
            condition: 'good',
            location: 'average',
            basePrice: 4000
        );

        $this->assertGreaterThan($lowPriceResult['estimated_value'], $highPriceResult['estimated_value']);
    }

    /** @test */
    public function it_handles_all_property_types()
    {
        $propertyTypes = ['detached', 'semi-detached', 'terraced', 'apartment', 'bungalow'];

        foreach ($propertyTypes as $type) {
            $result = $this->service->calculateHomeValuation(
                propertySize: 1500,
                bedrooms: 3,
                bathrooms: 2,
                yearBuilt: 2010,
                propertyType: $type,
                condition: 'good',
                location: 'average',
                basePrice: 3000
            );

            $this->assertIsArray($result);
            $this->assertGreaterThan(0, $result['estimated_value']);
            $this->assertEquals($type, $result['property_type']);
        }
    }

    /** @test */
    public function it_handles_all_condition_levels()
    {
        $conditions = ['excellent', 'good', 'fair', 'poor'];

        foreach ($conditions as $condition) {
            $result = $this->service->calculateHomeValuation(
                propertySize: 1500,
                bedrooms: 3,
                bathrooms: 2,
                yearBuilt: 2010,
                propertyType: 'terraced',
                condition: $condition,
                location: 'average',
                basePrice: 3000
            );

            $this->assertIsArray($result);
            $this->assertGreaterThan(0, $result['estimated_value']);
            $this->assertEquals($condition, $result['condition']);
        }
    }

    /** @test */
    public function it_handles_all_location_types()
    {
        $locations = ['prime', 'good', 'average', 'below-average'];

        foreach ($locations as $location) {
            $result = $this->service->calculateHomeValuation(
                propertySize: 1500,
                bedrooms: 3,
                bathrooms: 2,
                yearBuilt: 2010,
                propertyType: 'terraced',
                condition: 'good',
                location: $location,
                basePrice: 3000
            );

            $this->assertIsArray($result);
            $this->assertGreaterThan(0, $result['estimated_value']);
            $this->assertEquals($location, $result['location']);
        }
    }
}
