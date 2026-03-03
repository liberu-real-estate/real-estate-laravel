<?php

namespace Tests\Unit;

use App\Services\RadialSearchService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Property;
use App\Models\Team;
use App\Models\User;

class RadialSearchServiceTest extends TestCase
{
    use RefreshDatabase;

    private RadialSearchService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RadialSearchService();
    }

    /** @test */
    public function it_calculates_distance_between_two_points_in_miles()
    {
        // London to Manchester (approx. 163 miles)
        $distance = $this->service->calculateDistance(51.5074, -0.1278, 53.4808, -2.2426, 'miles');

        $this->assertGreaterThan(160, $distance);
        $this->assertLessThan(170, $distance);
    }

    /** @test */
    public function it_calculates_distance_between_two_points_in_km()
    {
        // London to Manchester (approx. 263 km)
        $distance = $this->service->calculateDistance(51.5074, -0.1278, 53.4808, -2.2426, 'km');

        $this->assertGreaterThan(258, $distance);
        $this->assertLessThan(270, $distance);
    }

    /** @test */
    public function it_returns_zero_distance_for_same_coordinates()
    {
        $distance = $this->service->calculateDistance(51.5074, -0.1278, 51.5074, -0.1278, 'miles');

        $this->assertEquals(0, $distance);
    }

    /** @test */
    public function it_throws_exception_for_zero_radius()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Radius must be greater than zero');

        $this->service->findPropertiesWithinRadius(51.5074, -0.1278, 0);
    }

    /** @test */
    public function it_throws_exception_for_negative_radius()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->service->findPropertiesWithinRadius(51.5074, -0.1278, -1);
    }

    /** @test */
    public function it_throws_exception_for_polygon_with_fewer_than_3_points()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('A polygon must have at least 3 vertices');

        $this->service->findPropertiesWithinPolygon([
            ['lat' => 51.5, 'lng' => -0.1],
            ['lat' => 51.6, 'lng' => -0.2],
        ]);
    }

    /** @test */
    public function it_finds_properties_within_radius()
    {
        $team = Team::create(['name' => 'Test Team', 'user_id' => 1, 'personal_team' => false]);
        $user = User::factory()->create(['current_team_id' => $team->id]);

        // Property within radius (London)
        $nearProperty = Property::factory()->create([
            'latitude' => 51.5080,
            'longitude' => -0.1281,
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);

        // Property far away (Manchester)
        $farProperty = Property::factory()->create([
            'latitude' => 53.4808,
            'longitude' => -2.2426,
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);

        $results = $this->service->findPropertiesWithinRadius(51.5074, -0.1278, 1, 'miles');

        $this->assertTrue($results->contains('id', $nearProperty->id));
        $this->assertFalse($results->contains('id', $farProperty->id));
    }

    /** @test */
    public function it_finds_properties_within_polygon()
    {
        $team = Team::create(['name' => 'Test Team', 'user_id' => 1, 'personal_team' => false]);
        $user = User::factory()->create(['current_team_id' => $team->id]);

        // Inside the polygon
        $inside = Property::factory()->create([
            'latitude' => 51.505,
            'longitude' => -0.09,
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);

        // Outside the polygon
        $outside = Property::factory()->create([
            'latitude' => 51.600,
            'longitude' => -0.50,
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);

        $polygon = [
            ['lat' => 51.50, 'lng' => -0.10],
            ['lat' => 51.51, 'lng' => -0.10],
            ['lat' => 51.51, 'lng' => -0.08],
            ['lat' => 51.50, 'lng' => -0.08],
        ];

        $results = $this->service->findPropertiesWithinPolygon($polygon);

        $this->assertTrue($results->contains('id', $inside->id));
        $this->assertFalse($results->contains('id', $outside->id));
    }
}
