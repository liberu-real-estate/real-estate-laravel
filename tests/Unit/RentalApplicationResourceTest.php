<?php

namespace Tests\Unit;

use App\Filament\Staff\Resources\RentalApplications\RentalApplicationResource;
use App\Models\RentalApplication;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Forms\ComponentContainer;
use Filament\Tables\Table;

class RentalApplicationResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_rental_application_resource_form()
    {
        $this->actingAs(RentalApplication::factory()->create());

        $form = RentalApplicationResource::form(new ComponentContainer());

        $this->assertNotNull($form->getSchema());
        $this->assertGreaterThan(0, count($form->getSchema()));
    }

    public function test_rental_application_resource_table()
    {
        $this->actingAs(RentalApplication::factory()->create());

        $table = RentalApplicationResource::table(new Table());

        $this->assertNotNull($table->getColumns());
        $this->assertGreaterThan(0, count($table->getColumns()));
    }

    public function test_rental_application_resource_relations()
    {
        $relations = RentalApplicationResource::getRelations();

        $this->assertIsArray($relations);
    }

    public function test_rental_application_resource_pages()
    {
        $pages = RentalApplicationResource::getPages();

        $this->assertIsArray($pages);
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_rental_application_resource_filters()
    {
        $filters = RentalApplicationResource::getFilters();

        $this->assertIsArray($filters);
    }

    public function test_rental_application_resource_actions()
    {
        $actions = RentalApplicationResource::getActions();

        $this->assertIsArray($actions);
    }
}