<?php

namespace Tests\Unit;

use App\Models\Tenant;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_tenant()
    {
        $tenantData = [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '9876543210',
        ];

        $tenant = Tenant::create($tenantData);

        $this->assertInstanceOf(Tenant::class, $tenant);
        $this->assertDatabaseHas('tenants', $tenantData);
    }

    public function test_tenant_relationships()
    {
        $tenant = Tenant::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $tenant->leases);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $tenant->payments);
    }

    public function test_tenant_full_name_attribute()
    {
        $tenant = Tenant::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
        ]);

        $this->assertEquals('Jane Smith', $tenant->full_name);
    }
}