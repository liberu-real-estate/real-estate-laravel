<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApiModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_modules_list_requires_authentication()
    {
        $response = $this->getJson('/api/modules');

        $response->assertStatus(401);
    }

    public function test_modules_status_requires_authentication()
    {
        $response = $this->getJson('/api/modules/status');

        $response->assertStatus(401);
    }

    public function test_authenticated_non_admin_cannot_access_modules_list()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/modules');

        $response->assertStatus(403);
    }

    public function test_authenticated_admin_can_access_modules_list()
    {
        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole($role);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/modules');

        $response->assertStatus(200);
    }

    public function test_authenticated_admin_can_access_modules_status()
    {
        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole($role);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/modules/status');

        $response->assertStatus(200);
    }
}
