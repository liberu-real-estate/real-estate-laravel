<?php

namespace Tests\Unit;

use App\Filament\Tenant\Resources\Leases\LeaseResource;
use App\Models\Lease;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Tables\Table;

class LeaseResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_lease_resource_form()
    {
        $this->markTestSkipped('Filament form tests require Livewire test setup.');
    }

    public function test_lease_resource_table()
    {
        $this->markTestSkipped('Filament table tests require Livewire test setup.');
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
    }
}