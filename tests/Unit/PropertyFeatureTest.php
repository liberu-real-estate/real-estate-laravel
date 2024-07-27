<?php

namespace Tests\Unit;

use App\Models\PropertyFeature;
use App\Models\Property;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PropertyFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_property_feature()
    {
        $featureData = [
            'name' => 'Swimming Pool',
            'description' => 'Large outdoor swimming pool',
        ];

        $feature = PropertyFeature::create($featureData);

        $this->assertInstanceOf(PropertyFeature::class, $feature);
        $this->assertDatabaseHas('property_features', $featureData);
    }

    public function test_property_feature_relationships()
    {
        $feature = PropertyFeature::factory()->create();
        $property = Property::factory()->create();

        $feature->properties()->attach($property);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $feature->properties);
        $this->assertTrue($feature->properties->contains($property));
    }
}