<?php

namespace Tests\Unit;

use App\Models\Property;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_property()
    {
        $propertyData = [
            'title' => 'Test Property',
            'description' => 'A test property description',
            'location' => 'Test Location',
            'price' => 250000,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'area_sqft' => 1500,
            'year_built' => 2020,
            'property_type' => 'House',
            'status' => 'For Sale',
        ];

        $property = Property::create($propertyData);

        $this->assertInstanceOf(Property::class, $property);
        $this->assertDatabaseHas('properties', $propertyData);
    }

    public function test_property_relationships()
    {
        $property = Property::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $property->appointments);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $property->transactions);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $property->reviews);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $property->features);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $property->images);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $property->bookings);
    }

    public function test_property_scopes()
    {
        $property = Property::factory()->create([
            'title' => 'Test Property',
            'price' => 200000,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'area_sqft' => 1500,
            'property_type' => 'House',
            'postal_code' => 'SW1A 1AA',
        ]);

        $this->assertCount(1, Property::search('Test')->get());
        $this->assertCount(1, Property::priceRange(150000, 250000)->get());
        $this->assertCount(1, Property::bedrooms(2, 4)->get());
        $this->assertCount(1, Property::bathrooms(1, 3)->get());
        $this->assertCount(1, Property::areaRange(1000, 2000)->get());
        $this->assertCount(1, Property::propertyType('House')->get());
        $this->assertCount(1, Property::postalCode('SW1A')->get());
    }

    public function test_postal_code_search()
    {
        Property::factory()->create(['postal_code' => 'SW1A 1AA']);
        Property::factory()->create(['postal_code' => 'SW1A 2BB']);
        Property::factory()->create(['postal_code' => 'NW1 1CC']);

        $this->assertCount(2, Property::postalCode('SW1A')->get());
        $this->assertCount(1, Property::postalCode('NW1')->get());
        $this->assertCount(0, Property::postalCode('SE1')->get());
    }

    public function test_get_available_dates_for_team()
    {
        $property = Property::factory()->create();
        $availableDates = $property->getAvailableDatesForTeam();

        $this->assertIsArray($availableDates);
        $this->assertNotEmpty($availableDates);
    }

    public function test_property_needs_walkability_update()
    {
        // Property without walkability data needs update
        $property = Property::factory()->create([
            'latitude' => 51.5074,
            'longitude' => -0.1278,
            'walkability_updated_at' => null,
        ]);
        $this->assertTrue($property->needsWalkabilityUpdate());

        // Property with recent walkability data doesn't need update
        $property->walkability_updated_at = now();
        $property->save();
        $this->assertFalse($property->needsWalkabilityUpdate());

        // Property with old walkability data needs update
        $property->walkability_updated_at = now()->subDays(31);
        $property->save();
        $this->assertTrue($property->needsWalkabilityUpdate());
    }

    public function test_update_walkability_scores()
    {
        $property = Property::factory()->create([
            'latitude' => 51.5074,
            'longitude' => -0.1278,
            'location' => '123 Main St',
            'postal_code' => 'SW1A 1AA',
        ]);

        $property->updateWalkabilityScores();
        $property->refresh();

        $this->assertNotNull($property->walkability_score);
        $this->assertNotNull($property->walkability_description);
        $this->assertNotNull($property->transit_score);
        $this->assertNotNull($property->bike_score);
        $this->assertNotNull($property->walkability_updated_at);
    }

    public function test_update_walkability_scores_requires_coordinates()
    {
        $property = Property::factory()->create([
            'latitude' => null,
            'longitude' => null,
            'location' => '123 Main St',
            'postal_code' => 'SW1A 1AA',
        ]);

        $property->updateWalkabilityScores();
        $property->refresh();

        // Should not update without coordinates
        $this->assertNull($property->walkability_score);
    }

    public function test_property_has_videos_media_collection()
    {
        $property = Property::factory()->create();
        
        // Verify media collections are properly registered
        $property->registerMediaCollections();
        
        // Test that the property can handle the videos collection
        $this->assertTrue($property->getMedia('videos')->isEmpty());
    }

    public function test_energy_rating_scope()
    {
        Property::factory()->create(['energy_rating' => 'A']);
        Property::factory()->create(['energy_rating' => 'B']);
        Property::factory()->create(['energy_rating' => 'C']);

        $this->assertCount(1, Property::energyRating('A')->get());
        $this->assertCount(1, Property::energyRating('B')->get());
        $this->assertCount(0, Property::energyRating('D')->get());
    }

    public function test_min_energy_score_scope()
    {
        Property::factory()->create(['energy_score' => 80]);
        Property::factory()->create(['energy_score' => 60]);
        Property::factory()->create(['energy_score' => 40]);

        $this->assertCount(2, Property::minEnergyScore(50)->get());
        $this->assertCount(1, Property::minEnergyScore(70)->get());
        $this->assertCount(3, Property::minEnergyScore(30)->get());
    }

    public function test_walkability_score_scope()
    {
        Property::factory()->create(['walkability_score' => 90]);
        Property::factory()->create(['walkability_score' => 70]);
        Property::factory()->create(['walkability_score' => 50]);

        $this->assertCount(2, Property::walkabilityScore(60)->get());
        $this->assertCount(1, Property::walkabilityScore(80)->get());
        $this->assertCount(3, Property::walkabilityScore(40)->get());
    }

    public function test_transit_score_scope()
    {
        Property::factory()->create(['transit_score' => 85]);
        Property::factory()->create(['transit_score' => 65]);
        Property::factory()->create(['transit_score' => 45]);

        $this->assertCount(2, Property::transitScore(50)->get());
        $this->assertCount(1, Property::transitScore(75)->get());
        $this->assertCount(3, Property::transitScore(40)->get());
    }

    public function test_bike_score_scope()
    {
        Property::factory()->create(['bike_score' => 88]);
        Property::factory()->create(['bike_score' => 68]);
        Property::factory()->create(['bike_score' => 48]);

        $this->assertCount(2, Property::bikeScore(55)->get());
        $this->assertCount(1, Property::bikeScore(80)->get());
        $this->assertCount(3, Property::bikeScore(30)->get());
    }

    public function test_featured_scope()
    {
        Property::factory()->create(['is_featured' => true]);
        Property::factory()->create(['is_featured' => true]);
        Property::factory()->create(['is_featured' => false]);

        $this->assertCount(2, Property::featured()->get());
    }

    public function test_country_scope()
    {
        Property::factory()->create(['country' => 'UK']);
        Property::factory()->create(['country' => 'UK']);
        Property::factory()->create(['country' => 'US']);

        $this->assertCount(2, Property::country('UK')->get());
        $this->assertCount(1, Property::country('US')->get());
        $this->assertCount(0, Property::country('FR')->get());
    }
}