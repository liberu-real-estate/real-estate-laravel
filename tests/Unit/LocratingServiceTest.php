<?php

namespace Tests\Unit;

use App\Services\LocratingService;
use Tests\TestCase;

class LocratingServiceTest extends TestCase
{
    private LocratingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LocratingService('test-api-key');
    }

    /** @test */
    public function it_throws_exception_for_zero_radius()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Radius must be greater than 0');

        $this->service->getSchoolsNearLocation(51.5074, -0.1278, 0);
    }

    /** @test */
    public function it_throws_exception_for_radius_too_large()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Radius must be greater than 0');

        $this->service->getSchoolsNearLocation(51.5074, -0.1278, 30);
    }

    /** @test */
    public function it_throws_exception_for_empty_school_id_when_getting_catchment()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('School ID cannot be empty');

        $this->service->getSchoolCatchmentArea('');
    }

    /** @test */
    public function it_throws_exception_for_empty_school_id_when_getting_ofsted()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('School ID cannot be empty');

        $this->service->getSchoolOfstedRating('');
    }

    /** @test */
    public function it_returns_error_when_api_key_not_configured()
    {
        $service = new LocratingService('');

        $result = $service->getSchoolsNearLocation(51.5074, -0.1278, 1.0);

        $this->assertArrayHasKey('error', $result);
        $this->assertStringContainsString('API key not configured', $result['error']);
    }

    /** @test */
    public function it_formats_schools_for_display()
    {
        $rawSchools = [
            [
                'id' => 'school-001',
                'name' => 'Greenfield Primary School',
                'phase' => 'primary',
                'type' => 'Academy',
                'ofsted_rating' => 1,
                'distance' => 0.3,
                'is_in_catchment' => true,
                'address' => '123 School Lane',
                'postcode' => 'SW1A 1AA',
            ],
            [
                'id' => 'school-002',
                'name' => 'Riverside Secondary',
                'phase' => 'secondary',
                'ofsted_rating' => 2,
                'distance' => 0.85,
                'is_in_catchment' => false,
            ],
        ];

        $formatted = $this->service->formatSchoolsForDisplay($rawSchools);

        $this->assertCount(2, $formatted);
        $this->assertEquals('Greenfield Primary School', $formatted[0]['name']);
        $this->assertEquals('Outstanding', $formatted[0]['ofsted_label']);
        $this->assertEquals(0.3, $formatted[0]['distance_miles']);
        $this->assertTrue($formatted[0]['is_in_catchment']);

        $this->assertEquals('Good', $formatted[1]['ofsted_label']);
        $this->assertFalse($formatted[1]['is_in_catchment']);
    }

    /** @test */
    public function it_formats_school_with_unknown_ofsted_rating()
    {
        $schools = [
            ['id' => 'school-001', 'name' => 'New School', 'ofsted_rating' => null],
        ];

        $formatted = $this->service->formatSchoolsForDisplay($schools);

        $this->assertEquals('Not yet inspected', $formatted[0]['ofsted_label']);
    }

    /** @test */
    public function it_accepts_phase_filter_for_primary()
    {
        // This test just verifies no exception is thrown with valid phases
        // (API request will fail gracefully without a real key)
        $service = new LocratingService('');
        $result = $service->getSchoolsNearLocation(51.5074, -0.1278, 1.0, 'primary');

        $this->assertArrayHasKey('error', $result);
    }

    /** @test */
    public function it_accepts_phase_filter_for_secondary()
    {
        $service = new LocratingService('');
        $result = $service->getSchoolsNearLocation(51.5074, -0.1278, 1.0, 'secondary');

        $this->assertArrayHasKey('error', $result);
    }
}
