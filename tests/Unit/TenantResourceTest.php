<?php

namespace Tests\Unit;

use App\Filament\Staff\Resources\Tenants\TenantResource;
use App\Models\Tenant;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Forms\ComponentContainer;
use Filament\Tables\Table;

class TenantResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_resource_form()
    {
        $this->actingAs(Tenant::factory()->create());

        $form = TenantResource::form(new ComponentContainer());

        $this->assertNotNull($form->getSchema());
        $this->assertGreaterThan(0, count($form->getSchema()));
    }

    public function test_tenant_resource_table()
    {
        $this->actingAs(Tenant::factory()->create());

        $table = TenantResource::table(new Table());

        $this->assertNotNull($table->getColumns());
        $this->assertGreaterThan(0, count($table->getColumns()));
    }

    public function test_tenant_resource_relations()
    {
        $relations = TenantResource::getRelations();

        $this->assertIsArray($relations);
    }

    public function test_tenant_resource_pages()
    {
        $pages = TenantResource::getPages();

        $this->assertIsArray($pages);
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }
}