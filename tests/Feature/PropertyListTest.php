<?php

use Tests\TestCase;
use Livewire\Livewire;
use App\Models\Property;

namespace Tests\Feature;

class PropertyListTest extends TestCase
{
    public function testCanMountWithProperties()
    {
        Property::factory()->count(5)->create();

        Livewire::test('property-list')
            ->assertSet('properties', Property::all()->toArray());
    }

    public function testCanUpdateSearchAndFilterProperties()
    {
        $matchingProperty = Property::factory()->create(['title' => 'Unique Title']);
        Property::factory()->count(4)->create();

        Livewire::test('property-list')
            ->set('search', 'Unique')
            ->assertSee($matchingProperty->title)
            ->assertDontSee(Property::where('title', '!=', 'Unique Title')->first()->title);
    }

    public function testRenderMethodReturnsView()
    {
        Livewire::test('property-list')
            ->assertViewIs('livewire.property-list');
    }
}
