<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Property;

class PropertyModelTest extends TestCase
{
    public function testScopeSearchFiltersProperties()
    {
        $property1 = Property::create(['title' => 'Beautiful Home', 'description' => 'A lovely spot in the city', 'location' => 'New York']);
        $property2 = Property::create(['title' => 'Cozy Cottage', 'description' => 'Perfect for a weekend getaway', 'location' => 'Vermont']);
        $property3 = Property::create(['title' => 'Modern Apartment', 'description' => 'City living at its finest', 'location' => 'San Francisco']);

        $searchTerm = 'Cozy';
        $filteredProperties = Property::search($searchTerm)->get();

        $this->assertCount(1, $filteredProperties);
        $this->assertTrue($filteredProperties->contains('id', $property2->id));

        $searchTerm = 'city';
        $filteredProperties = Property::search($searchTerm)->get();

        $this->assertCount(2, $filteredProperties);
        $this->assertTrue($filteredProperties->contains('id', $property1->id));
        $this->assertTrue($filteredProperties->contains('id', $property3->id));

        $searchTerm = 'nonexistent';
        $filteredProperties = Property::search($searchTerm)->get();

        $this->assertCount(0, $filteredProperties);
    }
}
