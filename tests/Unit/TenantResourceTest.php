<?php

namespace Tests\Unit;

use App\Filament\Staff\Resources\Tenants\TenantResource;
use App\Models\Tenant;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Tables\Table;

class TenantResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_resource_form()
    {
        $this->markTestSkipped('Filament form tests require Livewire test setup.');
    }

    public function test_tenant_resource_table()
    {
        $this->markTestSkipped('Filament table tests require Livewire test setup.');
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