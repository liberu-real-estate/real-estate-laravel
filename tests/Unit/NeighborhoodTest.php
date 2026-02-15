<?php

namespace Tests\Unit;

use App\Models\Neighborhood;
use App\Models\Property;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NeighborhoodTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_neighborhood()
    {
        $neighborhoodData = [
            'name' => 'Test Neighborhood',
            'description' => 'A test neighborhood description',
            'schools' => [
                ['name' => 'Test School', 'rating' => 8],
            ],
            'amenities' => ['Parks', 'Shopping Centers'],
            'crime_rate' => 'Low',
            'median_income' => 75000,
            'population' => 25000,
            'walk_score' => 85,
            'transit_score' => 70,
        ];

        $neighborhood = Neighborhood::create($neighborhoodData);

        $this->assertInstanceOf(Neighborhood::class, $neighborhood);
        $this->assertEquals('Test Neighborhood', $neighborhood->name);
        $this->assertEquals(75000, $neighborhood->median_income);
        $this->assertEquals(85, $neighborhood->walk_score);
    }

    public function test_neighborhood_has_properties()
    {
        $neighborhood = Neighborhood::factory()->create();
        $property = Property::factory()->create([
            'neighborhood_id' => $neighborhood->id,
        ]);

        $this->assertCount(1, $neighborhood->properties);
        $this->assertEquals($property->id, $neighborhood->properties->first()->id);
    }

    public function test_neighborhood_schools_are_cast_to_array()
    {
        $neighborhood = Neighborhood::factory()->create();

        $this->assertIsArray($neighborhood->schools);
    }

    public function test_neighborhood_amenities_are_cast_to_array()
    {
        $neighborhood = Neighborhood::factory()->create();

        $this->assertIsArray($neighborhood->amenities);
    }

    public function test_neighborhood_last_updated_is_cast_to_datetime()
    {
        $neighborhood = Neighborhood::factory()->create();

        $this->assertInstanceOf(\DateTime::class, $neighborhood->last_updated);
    }
}
