<?php

namespace Tests\Unit;

use App\Filament\Tenant\Resources\Leases\LeaseResource;
use App\Models\Lease;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Forms\ComponentContainer;
use Filament\Tables\Table;

class LeaseResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_lease_resource_form()
    {
        $this->actingAs(Lease::factory()->create());

        $form = LeaseResource::form(new ComponentContainer());

        $this->assertNotNull($form->getSchema());
        $this->assertGreaterThan(0, count($form->getSchema()));
    }

    public function test_lease_resource_table()
    {
        $this->actingAs(Lease::factory()->create());

        $table = LeaseResource::table(new Table());

        $this->assertNotNull($table->getColumns());
        $this->assertGreaterThan(0, count($table->getColumns()));
    }

    public function test_lease_resource_relations()
    {
        $relations = LeaseResource::getRelations();

        $this->assertIsArray($relations);
    }

    public function test_lease_resource_pages()
    {
        $pages = LeaseResource::getPages();

        $this->assertIsArray($pages);
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }
}