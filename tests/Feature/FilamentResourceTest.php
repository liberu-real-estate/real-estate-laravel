<?php

namespace Tests\Feature;

use App\Filament\Resources\AlertResource;
use App\Filament\Resources\BookingResource;
use App\Models\Alert;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FilamentResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_alert_resource_has_correct_model(): void
    {
        $this->assertEquals(Alert::class, AlertResource::getModel());
    }

    public function test_alert_resource_has_required_pages(): void
    {
        $pages = AlertResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_alert_resource_returns_empty_relations(): void
    {
        $relations = AlertResource::getRelations();

        $this->assertIsArray($relations);
    }

    public function test_alert_resource_returns_widgets(): void
    {
        $widgets = AlertResource::getWidgets();

        $this->assertIsArray($widgets);
    }

    public function test_booking_resource_has_correct_model(): void
    {
        $this->assertEquals(Booking::class, BookingResource::getModel());
    }

    public function test_booking_resource_has_required_pages(): void
    {
        $pages = BookingResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_admin_panel_is_accessible(): void
    {
        // Use the same helper pattern as ExampleTest
        $user = $this->createUserWithRole('admin');
        $this->actingAs($user);
        $this->get('/admin')->assertStatus(200);
    }

    protected function createUserWithRole(string $roleName): User
    {
        $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole($role);
        return $user;
    }
}
