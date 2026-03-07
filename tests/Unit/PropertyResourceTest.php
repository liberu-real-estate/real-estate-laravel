<?php

namespace Tests\Unit;

use App\Filament\Staff\Resources\Properties\PropertyResource;
use App\Models\Property;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Forms\ComponentContainer;
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

        // Assuming isHmo() method exists on Property model
        $property->isHmo = true;
        $this->assertTrue(PropertyResource::canViewRelation('rooms', $property));

        $property->isHmo = false;
        $this->assertFalse(PropertyResource::canViewRelation('rooms', $property));
    }
}