<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Property;
use Livewire\Livewire;
use App\Http\Livewire\PropertyComparison;

class PropertyComparisonTest extends TestCase
{
    public function test_property_comparison_component_can_load_properties()
    {
        $properties = Property::factory()->count(3)->create();
        $propertyIds = $properties->pluck('property_id')->join(',');

        Livewire::test(PropertyComparison::class, ['propertyIds' => $propertyIds])
            ->assertSet('propertyIds', explode(',', $propertyIds))
            ->assertCount('properties', 3);
    }

    public function test_property_comparison_page_displays_correctly()
    {
        $properties = Property::factory()->count(2)->create();
        $propertyIds = $properties->pluck('property_id')->join(',');

        $response = $this->get(route('property.compare', ['propertyIds' => $propertyIds]));

        $response->assertStatus(200)
            ->assertSeeLivewire(PropertyComparison::class)
            ->assertSee($properties[0]->title)
            ->assertSee($properties[1]->title);
    }
}