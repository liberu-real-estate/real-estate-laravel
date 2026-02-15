<?php

namespace Tests\Unit;

use App\Services\PropertyTaxEstimatorService;
use App\Services\StampDutyCalculatorService;
use Tests\TestCase;

class PropertyTaxEstimatorServiceTest extends TestCase
{
    protected PropertyTaxEstimatorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $stampDutyCalculator = new StampDutyCalculatorService();
        $this->service = new PropertyTaxEstimatorService($stampDutyCalculator);
    }

    /** @test */
    public function it_calculates_uk_property_taxes_for_first_time_buyer()
    {
        $result = $this->service->estimatePropertyTax(250000, 'UK', ['buyer_type' => 'first_time_buyer']);

        $this->assertEquals('United Kingdom', $result['country']);
        $this->assertEquals(250000, $result['purchase_price']);
        $this->assertEquals('first_time_buyer', $result['buyer_type']);
        $this->assertEquals(0, $result['stamp_duty']); // First time buyer pays no stamp duty up to £300k
        $this->assertArrayHasKey('total_tax', $result);
        $this->assertArrayHasKey('total_cost', $result);
        $this->assertArrayHasKey('breakdown', $result);
    }

    /** @test */
    public function it_calculates_uk_property_taxes_for_home_mover()
    {
        $result = $this->service->estimatePropertyTax(300000, 'UK', ['buyer_type' => 'home_mover']);

        $this->assertEquals('United Kingdom', $result['country']);
        $this->assertEquals(300000, $result['purchase_price']);
        $this->assertEquals('home_mover', $result['buyer_type']);
        $this->assertEquals(2500, $result['stamp_duty']); // (300000 - 250000) * 0.05 = 2500
        $this->assertGreaterThan(0, $result['total_tax']);
        $this->assertArrayHasKey('additional_costs', $result);
    }

    /** @test */
    public function it_calculates_uk_property_taxes_for_additional_property()
    {
        $result = $this->service->estimatePropertyTax(300000, 'UK', ['buyer_type' => 'additional_property']);

        $this->assertEquals('United Kingdom', $result['country']);
        $this->assertEquals('additional_property', $result['buyer_type']);
        $this->assertEquals(11500, $result['stamp_duty']); // 3% surcharge applies
        $this->assertGreaterThan(2500, $result['stamp_duty']); // Should be more than home_mover
    }

    /** @test */
    public function it_includes_uk_additional_costs()
    {
        $result = $this->service->estimatePropertyTax(400000, 'UK');

        $this->assertArrayHasKey('additional_costs', $result);
        $this->assertArrayHasKey('legal_fees', $result['additional_costs']);
        $this->assertArrayHasKey('survey_fees', $result['additional_costs']);
        $this->assertArrayHasKey('land_registry_fees', $result['additional_costs']);
        
        $this->assertGreaterThan(0, $result['additional_costs']['legal_fees']);
        $this->assertGreaterThan(0, $result['additional_costs']['survey_fees']);
        $this->assertGreaterThan(0, $result['additional_costs']['land_registry_fees']);
    }

    /** @test */
    public function it_calculates_us_property_taxes()
    {
        $result = $this->service->estimatePropertyTax(500000, 'US');

        $this->assertEquals('United States', $result['country']);
        $this->assertEquals(500000, $result['purchase_price']);
        $this->assertArrayHasKey('transfer_tax', $result);
        $this->assertArrayHasKey('annual_property_tax', $result);
        $this->assertArrayHasKey('additional_costs', $result);
        
        // Default 1% transfer tax
        $this->assertEquals(5000, $result['transfer_tax']);
        
        // Default 1.1% annual property tax
        $this->assertEquals(5500, $result['annual_property_tax']);
    }

    /** @test */
    public function it_calculates_generic_property_taxes_for_other_countries()
    {
        $result = $this->service->estimatePropertyTax(300000, 'FR', ['country_name' => 'France']);

        $this->assertEquals('France', $result['country']);
        $this->assertEquals(300000, $result['purchase_price']);
        $this->assertArrayHasKey('property_transfer_tax', $result);
        $this->assertArrayHasKey('additional_costs', $result);
        
        // Default 3% transfer tax
        $this->assertEquals(9000, $result['property_transfer_tax']);
    }

    /** @test */
    public function it_handles_different_country_code_formats()
    {
        $resultUK = $this->service->estimatePropertyTax(200000, 'uk');
        $resultGB = $this->service->estimatePropertyTax(200000, 'GB');
        $resultFull = $this->service->estimatePropertyTax(200000, 'United Kingdom');

        $this->assertEquals('United Kingdom', $resultUK['country']);
        $this->assertEquals('United Kingdom', $resultGB['country']);
        $this->assertEquals('United Kingdom', $resultFull['country']);
    }

    /** @test */
    public function it_defaults_to_home_mover_for_invalid_buyer_type()
    {
        $result = $this->service->estimatePropertyTax(300000, 'UK', ['buyer_type' => 'invalid_type']);

        $this->assertEquals('home_mover', $result['buyer_type']);
    }

    /** @test */
    public function it_calculates_total_cost_correctly()
    {
        $result = $this->service->estimatePropertyTax(300000, 'UK', ['buyer_type' => 'home_mover']);

        $expectedTotal = $result['purchase_price'] 
            + $result['total_tax'] 
            + $result['total_additional_costs'];

        $this->assertEquals($expectedTotal, $result['total_cost']);
    }

    /** @test */
    public function it_provides_breakdown_of_all_costs()
    {
        $result = $this->service->estimatePropertyTax(350000, 'UK');

        $this->assertArrayHasKey('breakdown', $result);
        $this->assertArrayHasKey('Purchase Price', $result['breakdown']);
        $this->assertArrayHasKey('Total Cost', $result['breakdown']);
        
        $breakdownTotal = $result['breakdown']['Total Cost'];
        $this->assertEquals($result['total_cost'], $breakdownTotal);
    }

    /** @test */
    public function it_scales_legal_fees_with_property_price()
    {
        $result100k = $this->service->estimatePropertyTax(100000, 'UK');
        $result500k = $this->service->estimatePropertyTax(500000, 'UK');

        $this->assertLessThan(
            $result500k['additional_costs']['legal_fees'],
            $result100k['additional_costs']['legal_fees']
        );
    }

    /** @test */
    public function it_scales_survey_fees_with_property_price()
    {
        $result100k = $this->service->estimatePropertyTax(100000, 'UK');
        $result500k = $this->service->estimatePropertyTax(500000, 'UK');

        $this->assertLessThan(
            $result500k['additional_costs']['survey_fees'],
            $result100k['additional_costs']['survey_fees']
        );
    }

    /** @test */
    public function it_calculates_land_registry_fees_correctly()
    {
        $result50k = $this->service->estimatePropertyTax(50000, 'UK');
        $result150k = $this->service->estimatePropertyTax(150000, 'UK');
        $result600k = $this->service->estimatePropertyTax(600000, 'UK');

        $this->assertEquals(40, $result50k['additional_costs']['land_registry_fees']);
        $this->assertEquals(190, $result150k['additional_costs']['land_registry_fees']);
        $this->assertEquals(540, $result600k['additional_costs']['land_registry_fees']);
    }

    /** @test */
    public function it_calculates_effective_tax_rate()
    {
        $result = $this->service->estimatePropertyTax(300000, 'UK', ['buyer_type' => 'home_mover']);

        $this->assertArrayHasKey('effective_tax_rate', $result);
        $this->assertGreaterThanOrEqual(0, $result['effective_tax_rate']);
        
        // Verify effective tax rate calculation
        $expectedRate = ($result['total_tax'] / $result['purchase_price']) * 100;
        $this->assertEquals(round($expectedRate, 2), $result['effective_tax_rate']);
    }
}
