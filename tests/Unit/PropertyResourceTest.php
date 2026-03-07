<?php

namespace Tests\Unit;

use App\Filament\Staff\Resources\Properties\PropertyResource;
use App\Models\Property;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Tables\Table;

class PropertyResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_resource_form()
    {
        $this->markTestSkipped('Filament form tests require Livewire test setup.');
    }

    public function test_property_resource_table()
    {
        $this->markTestSkipped('Filament table tests require Livewire test setup.');
    }

    public function test_property_resource_relations()
    {
        $relations = PropertyResource::getRelations();

        $this->assertIsArray($relations);
        $this->assertContains('App\Filament\Staff\Resources\RelationManagers\ReviewsRelationManager', $relations);
        $this->assertContains('App\Filament\Staff\Resources\RelationManagers\RoomsRelationManager', $relations);
    }

    public function test_property_resource_pages()
    {
        $pages = PropertyResource::getPages();

        $this->assertIsArray($pages);
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_property_resource_filters()
    {
        $this->markTestSkipped('Filament filter tests require Livewire test setup.');
    }

    public function test_can_view_relation()
    {
        $property = Property::factory()->create();

        $this->assertTrue(PropertyResource::canViewRelation('reviews', $property));

        // Test HMO property type shows rooms relation
        $hmoProperty = Property::factory()->create(['property_type' => 'HMO']);
        $this->assertTrue(PropertyResource::canViewRelation('rooms', $hmoProperty));

        // Test non-HMO property type hides rooms relation
        $nonHmoProperty = Property::factory()->create(['property_type' => 'detached']);
        $this->assertFalse(PropertyResource::canViewRelation('rooms', $nonHmoProperty));
    }
}