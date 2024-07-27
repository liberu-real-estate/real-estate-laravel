<?php

namespace Tests\Unit;

use App\Filament\Resources\AlertResource;
use App\Models\Alert;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Forms\ComponentContainer;
use Filament\Tables\Table;

class AlertResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_alert_resource_form()
    {
        $this->actingAs(Alert::factory()->create());

        $form = AlertResource::form(new ComponentContainer());

        $this->assertNotNull($form->getSchema());
        $this->assertGreaterThan(0, count($form->getSchema()));
    }

    public function test_alert_resource_table()
    {
        $this->actingAs(Alert::factory()->create());

        $table = AlertResource::table(new Table());

        $this->assertNotNull($table->getColumns());
        $this->assertGreaterThan(0, count($table->getColumns()));
    }

    public function test_alert_resource_relations()
    {
        $relations = AlertResource::getRelations();

        $this->assertIsArray($relations);
    }

    public function test_alert_resource_pages()
    {
        $pages = AlertResource::getPages();

        $this->assertIsArray($pages);
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_alert_resource_filters()
    {
        $filters = AlertResource::getFilters();

        $this->assertIsArray($filters);
    }
}