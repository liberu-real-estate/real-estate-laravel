<?php

namespace Tests\Unit;

use App\Filament\Staff\Resources\PropertyFeatures\PropertyFeatureResource;
use App\Models\PropertyFeature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Forms\ComponentContainer;
use Filament\Tables\Table;

class PropertyFeatureResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_feature_resource_form()
    {
        $this->markTestSkipped('Filament form tests require Livewire test setup.');
    }

    public function test_property_feature_resource_table()
    {
        $this->markTestSkipped('Filament table tests require Livewire test setup.');
    }

    public function test_property_feature_resource_relations()
    {
        $relations = PropertyFeatureResource::getRelations();

        $this->assertIsArray($relations);
    }

    public function test_property_feature_resource_pages()
    {
        $pages = PropertyFeatureResource::getPages();

        $this->assertIsArray($pages);
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_property_feature_resource_filters()
    {
        $filters = PropertyFeatureResource::getFilters();

        $this->assertIsArray($filters);
    }

    public function test_property_feature_resource_actions()
    {
        $actions = PropertyFeatureResource::getActions();

        $this->assertIsArray($actions);
    }
}