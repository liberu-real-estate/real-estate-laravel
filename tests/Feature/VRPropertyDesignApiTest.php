<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\User;
use App\Models\VRDesign;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VRPropertyDesignApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Property $property;
    protected Team $team;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->team = Team::factory()->create();
        $this->user = User::factory()->create([
            'current_team_id' => $this->team->id,
        ]);
        $this->property = Property::factory()->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
    }

    public function test_get_design_styles()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/vr-design/styles');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'styles',
                ],
            ]);
    }

    public function test_get_furniture_categories()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/vr-design/furniture-categories');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'categories',
                ],
            ]);
    }

    public function test_get_room_types()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/vr-design/room-types');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'room_types',
                ],
            ]);
    }

    public function test_get_supported_devices()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/vr-design/devices');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'devices',
                ],
            ]);
    }

    public function test_create_design()
    {
        $response = $this->actingAs($this->user)
            ->postJson("/api/properties/{$this->property->id}/vr-designs", [
                'name' => 'Test VR Design',
                'description' => 'A test VR design',
                'style' => 'modern',
                'design_data' => ['test' => 'data'],
                'is_public' => false,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'VR design created successfully',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'design' => [
                        'id',
                        'name',
                        'description',
                        'style',
                        'property_id',
                        'user_id',
                    ],
                ],
            ]);

        $this->assertDatabaseHas('vr_designs', [
            'name' => 'Test VR Design',
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
        ]);
    }

    public function test_create_design_validation_fails()
    {
        $response = $this->actingAs($this->user)
            ->postJson("/api/properties/{$this->property->id}/vr-designs", [
                // Missing required 'name' and 'design_data'
                'description' => 'Invalid design',
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ])
            ->assertJsonValidationErrors(['name', 'design_data']);
    }

    public function test_create_design_invalid_style()
    {
        $response = $this->actingAs($this->user)
            ->postJson("/api/properties/{$this->property->id}/vr-designs", [
                'name' => 'Test Design',
                'design_data' => ['test' => 'data'],
                'style' => 'invalid_style',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['style']);
    }

    public function test_get_property_designs()
    {
        VRDesign::factory()->count(3)->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/properties/{$this->property->id}/vr-designs");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'designs',
                    'count',
                ],
            ])
            ->assertJsonPath('data.count', 3);
    }

    public function test_get_property_designs_public_only()
    {
        VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'is_public' => true,
        ]);

        VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'is_public' => false,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/properties/{$this->property->id}/vr-designs?public_only=true");

        $response->assertStatus(200)
            ->assertJsonPath('data.count', 1);
    }

    public function test_get_design()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/vr-designs/{$design->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonPath('data.design.id', $design->id);
    }

    public function test_get_nonexistent_design()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/vr-designs/99999');

        $response->assertStatus(404);
    }

    public function test_update_design()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'name' => 'Original Name',
        ]);

        $response = $this->actingAs($this->user)
            ->putJson("/api/vr-designs/{$design->id}", [
                'name' => 'Updated Name',
                'description' => 'Updated description',
                'style' => 'luxury',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'VR design updated successfully',
            ])
            ->assertJsonPath('data.design.name', 'Updated Name');

        $this->assertDatabaseHas('vr_designs', [
            'id' => $design->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_update_design_unauthorized()
    {
        $otherUser = User::factory()->create();
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $otherUser->id,
            'team_id' => $this->team->id,
        ]);

        $response = $this->actingAs($this->user)
            ->putJson("/api/vr-designs/{$design->id}", [
                'name' => 'Hacked Name',
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized',
            ]);
    }

    public function test_delete_design()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/vr-designs/{$design->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'VR design deleted successfully',
            ]);

        $this->assertSoftDeleted('vr_designs', ['id' => $design->id]);
    }

    public function test_delete_design_unauthorized()
    {
        $otherUser = User::factory()->create();
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $otherUser->id,
            'team_id' => $this->team->id,
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/vr-designs/{$design->id}");

        $response->assertStatus(403);
    }

    public function test_add_furniture()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/vr-designs/{$design->id}/furniture", [
                'category' => 'seating',
                'type' => 'Sofa',
                'position' => [0, 0, 0],
                'rotation' => [0, 90, 0],
                'scale' => [1, 1, 1],
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Furniture added successfully',
            ]);

        $design->refresh();
        $this->assertNotEmpty($design->furniture_items);
        $this->assertCount(1, $design->furniture_items);
    }

    public function test_add_furniture_validation_fails()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/vr-designs/{$design->id}/furniture", [
                // Missing required fields
                'category' => 'seating',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type', 'position']);
    }

    public function test_remove_furniture()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'furniture_items' => [
                [
                    'id' => 'furniture_123',
                    'category' => 'seating',
                    'type' => 'Sofa',
                    'position' => [0, 0, 0],
                    'rotation' => [0, 0, 0],
                    'scale' => [1, 1, 1],
                    'material' => [],
                    'created_at' => now()->toIso8601String(),
                ],
            ],
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/vr-designs/{$design->id}/furniture/furniture_123");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Furniture removed successfully',
            ]);

        $design->refresh();
        $this->assertEmpty($design->furniture_items);
    }

    public function test_clone_design()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'name' => 'Original Design',
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/vr-designs/{$design->id}/clone", [
                'name' => 'Cloned Design',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Design cloned successfully',
            ])
            ->assertJsonPath('data.design.name', 'Cloned Design');

        $this->assertDatabaseHas('vr_designs', [
            'name' => 'Cloned Design',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_get_templates()
    {
        VRDesign::factory()->count(2)->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'is_template' => true,
            'is_public' => true,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/vr-design/templates');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonPath('data.count', 2);
    }

    public function test_get_templates_filtered_by_style()
    {
        VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'is_template' => true,
            'style' => 'modern',
        ]);

        VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'is_template' => true,
            'style' => 'traditional',
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/vr-design/templates?style=modern');

        $response->assertStatus(200)
            ->assertJsonPath('data.count', 1);
    }

    public function test_upload_thumbnail()
    {
        Storage::fake('public');

        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $file = UploadedFile::fake()->image('thumbnail.jpg');

        $response = $this->actingAs($this->user)
            ->postJson("/api/vr-designs/{$design->id}/thumbnail", [
                'thumbnail' => $file,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Thumbnail uploaded successfully',
            ]);

        $design->refresh();
        $this->assertNotNull($design->thumbnail_path);
        Storage::disk('public')->assertExists($design->thumbnail_path);
    }

    public function test_upload_thumbnail_validation_fails()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $file = UploadedFile::fake()->create('document.pdf', 1000);

        $response = $this->actingAs($this->user)
            ->postJson("/api/vr-designs/{$design->id}/thumbnail", [
                'thumbnail' => $file,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['thumbnail']);
    }

    public function test_export_design()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'name' => 'Export Test',
            'design_data' => ['test' => 'data'],
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/vr-designs/{$design->id}/export");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'style',
                    'design_data',
                    'metadata',
                ],
            ]);
    }

    public function test_unauthenticated_access_denied()
    {
        $response = $this->getJson('/api/vr-design/styles');

        $response->assertStatus(401);
    }

    public function test_property_not_found()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/properties/99999/vr-designs', [
                'name' => 'Test',
                'design_data' => ['test' => 'data'],
            ]);

        $response->assertStatus(404);
    }
}
