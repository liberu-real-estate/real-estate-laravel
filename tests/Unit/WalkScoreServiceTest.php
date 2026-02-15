<?php

namespace Tests\Unit;

use App\Services\WalkScoreService;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class WalkScoreServiceTest extends TestCase
{
    protected $walkScoreService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->walkScoreService = new WalkScoreService();
    }

    public function test_returns_mock_data_when_api_key_not_configured()
    {
        Config::set('services.walkscore.api_key', null);
        
        $result = $this->walkScoreService->getWalkScore(
            '123 Main St, London',
            51.5074,
            -0.1278
        );

        $this->assertNotNull($result);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('walk_score', $result);
        $this->assertArrayHasKey('walk_description', $result);
        $this->assertArrayHasKey('transit_score', $result);
        $this->assertArrayHasKey('bike_score', $result);
    }

    public function test_mock_scores_are_within_valid_range()
    {
        Config::set('services.walkscore.api_key', null);
        
        $result = $this->walkScoreService->getWalkScore(
            '123 Main St, London',
            51.5074,
            -0.1278
        );

        $this->assertGreaterThanOrEqual(0, $result['walk_score']);
        $this->assertLessThanOrEqual(100, $result['walk_score']);
        $this->assertGreaterThanOrEqual(0, $result['transit_score']);
        $this->assertLessThanOrEqual(100, $result['transit_score']);
        $this->assertGreaterThanOrEqual(0, $result['bike_score']);
        $this->assertLessThanOrEqual(100, $result['bike_score']);
    }

    public function test_mock_scores_are_deterministic()
    {
        Config::set('services.walkscore.api_key', null);
        
        $result1 = $this->walkScoreService->getWalkScore(
            '123 Main St, London',
            51.5074,
            -0.1278
        );

        $result2 = $this->walkScoreService->getWalkScore(
            '123 Main St, London',
            51.5074,
            -0.1278
        );

        $this->assertEquals($result1['walk_score'], $result2['walk_score']);
        $this->assertEquals($result1['transit_score'], $result2['transit_score']);
        $this->assertEquals($result1['bike_score'], $result2['bike_score']);
    }

    public function test_returns_proper_descriptions()
    {
        Config::set('services.walkscore.api_key', null);
        
        $result = $this->walkScoreService->getWalkScore(
            '123 Main St, London',
            51.5074,
            -0.1278
        );

        $this->assertNotEmpty($result['walk_description']);
        $this->assertNotEmpty($result['transit_description']);
        $this->assertNotEmpty($result['bike_description']);
        $this->assertIsString($result['walk_description']);
        $this->assertIsString($result['transit_description']);
        $this->assertIsString($result['bike_description']);
    }

    public function test_handles_api_failure_gracefully()
    {
        Config::set('services.walkscore.api_key', 'test_key');
        Config::set('services.walkscore.base_uri', 'https://api.walkscore.com');

        Http::fake([
            'api.walkscore.com/*' => Http::response([], 500)
        ]);

        $result = $this->walkScoreService->getWalkScore(
            '123 Main St, London',
            51.5074,
            -0.1278
        );

        // Should fall back to mock data
        $this->assertNotNull($result);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('walk_score', $result);
    }

    public function test_validates_scores_within_range()
    {
        Config::set('services.walkscore.api_key', 'test_key');
        Config::set('services.walkscore.base_uri', 'https://api.walkscore.com');

        // Mock API response with out-of-range scores
        Http::fake([
            'api.walkscore.com/*' => Http::response([
                'walkscore' => 150, // Invalid: > 100
                'description' => 'Test',
                'transit' => ['score' => -10, 'description' => 'Test'], // Invalid: < 0
                'bike' => ['score' => 75, 'description' => 'Test'], // Valid
            ], 200)
        ]);

        $result = $this->walkScoreService->getWalkScore(
            '123 Main St, London',
            51.5074,
            -0.1278
        );

        // Scores should be clamped to valid range
        $this->assertEquals(100, $result['walk_score']); // Clamped from 150
        $this->assertEquals(0, $result['transit_score']); // Clamped from -10
        $this->assertEquals(75, $result['bike_score']); // Unchanged (valid)
    }
}
