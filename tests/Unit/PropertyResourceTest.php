<?php

namespace Tests\Unit;

use App\Filament\Staff\Resources\PropertyResource;
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
        $this->actingAs(Property::factory()->create());

        $form = PropertyResource::form(new ComponentContainer());

        $this->assertNotNull($form->getSchema());
        $this->assertGreaterThan(0, count($form->getSchema()));
    }

    public function test_property_resource_table()
    {
        $this->actingAs(Property::factory()->create());

        $table = PropertyResource::table(new Table());

        $this->assertNotNull($table->getColumns());
        $this->assertGreaterThan(0, count($table->getColumns()));
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
        $filters = PropertyResource::getFilters();

        $this->assertIsArray($filters);
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