<?php

namespace Tests\Unit;

use App\Filament\Tenant\Resources\MaintenanceRequests\MaintenanceRequestResource;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Forms\ComponentContainer;
use Filament\Tables\Table;

class MaintenanceRequestResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_maintenance_request_resource_form()
    {
        $this->actingAs(User::factory()->create());

        $form = MaintenanceRequestResource::form(new ComponentContainer());

        $this->assertNotNull($form->getSchema());
        $this->assertGreaterThan(0, count($form->getSchema()));
    }

    public function test_maintenance_request_resource_table()
    {
        $this->actingAs(User::factory()->create());

        $table = MaintenanceRequestResource::table(new Table());

        $this->assertNotNull($table->getColumns());
        $this->assertGreaterThan(0, count($table->getColumns()));
    }

    public function test_maintenance_request_resource_relations()
    {
        $relations = MaintenanceRequestResource::getRelations();

        $this->assertIsArray($relations);
    }

    public function test_maintenance_request_resource_pages()
    {
        $pages = MaintenanceRequestResource::getPages();

        $this->assertIsArray($pages);
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_maintenance_request_resource_filters()
    {
        $filters = MaintenanceRequestResource::getFilters();

        $this->assertIsArray($filters);
    }

    public function test_maintenance_request_resource_widgets()
    {
        $widgets = MaintenanceRequestResource::getWidgets();

        $this->assertIsArray($widgets);
    }
}