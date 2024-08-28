<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test the root route ("/") returns a successful response.
     */
    public function test_the_root_route_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test the "/app" route returns a successful response.
     */
    public function test_the_app_route_returns_a_successful_response(): void
    {
        $this->actingAs($this->createUserWithRole('staff'));
        $response = $this->get('/app');
        $response->assertStatus(200);
    }

    /**
     * Test the "/admin" route returns a successful response.
     */
    public function test_the_admin_route_returns_a_successful_response(): void
    {
        $this->actingAs($this->createUserWithRole('admin'));
        $response = $this->get('/admin');
        $response->assertStatus(200);
    }

    /**
     * Helper function to create a user with a specific role.
     *
     * @param string $roleName The name of the role to assign.
     * @param array $userAttributes Additional attributes for the user.
     * @return \App\Models\User The created user with the assigned role.
     */
    protected function createUserWithRole(string $roleName, array $userAttributes = []): User
    {
        $role = Role::firstOrCreate(['name' => $roleName]);
        $user = User::factory()->create($userAttributes);
        $user->assignRole($role);
        return $user;
    }
}
