<?php

namespace Tests\Unit;

use App\Filament\Staff\Resources\PropertyFeatureResource;
use App\Models\PropertyFeature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Forms\ComponentContainer;

class PropertyFeatureResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_feature_resource_form()
    {
        $this->actingAs(PropertyFeature::factory()->create());

        $form = PropertyFeatureResource::form(new ComponentContainer());

        $this->assertNotNull($form->getSchema());
        $this->assertCount(2, $form->getSchema());
    }

    public function test_property_feature_resource_table()
    {
        $this->actingAs(PropertyFeature::factory()->create());

        $table = PropertyFeatureResource::table(new \Filament\Tables\Table());

        $this->assertNotNull($table->getColumns());
        $this->assertCount(2, $table->getColumns());
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
}