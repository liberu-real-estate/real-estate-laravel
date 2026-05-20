<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\User;
use App\Models\Team;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ARTourControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_can_get_ar_tour_config_for_available_property()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'ar_tour_enabled' => true,
        ]);

        $file = UploadedFile::fake()->create('model.glb', 1024, 'model/gltf-binary');
        $property->addMedia($file)->toMediaCollection('3d_models');

        $response = $this->getJson(route('property.ar-tour.config', $property));

        $response->assertStatus(200)
            ->assertJson([
                'available' => true,
            ])
            ->assertJsonStructure([
                'available',
                'config' => [
                    'model_url',
                    'scale',
                    'placement_guide',
                    'ar_modes',
                ],
                'property' => [
                    'id',
                    'title',
                    'location',
                ],
            ]);
    }

    public function test_returns_404_for_unavailable_ar_tour()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'ar_tour_enabled' => false,
        ]);

        $response = $this->getJson(route('property.ar-tour.config', $property));

        $response->assertStatus(404)
            ->assertJson([
                'available' => false,
            ]);
    }

    public function test_can_check_ar_tour_availability()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'ar_tour_enabled' => true,
        ]);

        $file = UploadedFile::fake()->create('model.glb', 1024, 'model/gltf-binary');
        $property->addMedia($file)->toMediaCollection('3d_models');

        $response = $this->getJson(route('property.ar-tour.availability', $property));

        $response->assertStatus(200)
            ->assertJson([
                'available' => true,
            ])
            ->assertJsonStructure([
                'available',
                'stats',
            ]);
    }

    public function test_authenticated_user_can_enable_ar_tour()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'ar_tour_enabled' => false,
        ]);

        $file = UploadedFile::fake()->create('model.glb', 1024, 'model/gltf-binary');
        $property->addMedia($file)->toMediaCollection('3d_models');

        $response = $this->actingAs($user)
            ->postJson(route('property.ar-tour.enable', $property), [
                'ar_model_scale' => 1.5,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertTrue($property->fresh()->ar_tour_enabled);
    }

    public function test_cannot_enable_ar_tour_without_3d_model()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('property.ar-tour.enable', $property));

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_authenticated_user_can_disable_ar_tour()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'ar_tour_enabled' => true,
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('property.ar-tour.disable', $property));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertFalse($property->fresh()->ar_tour_enabled);
    }

    public function test_authenticated_user_can_update_ar_tour_settings()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'ar_tour_enabled' => true,
        ]);

        $response = $this->actingAs($user)
            ->putJson(route('property.ar-tour.update-settings', $property), [
                'auto_rotate' => false,
                'shadow_intensity' => 2,
                'ar_model_scale' => 2.5,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $property->refresh();
        $this->assertFalse($property->ar_tour_settings['auto_rotate']);
        $this->assertEquals(2, $property->ar_tour_settings['shadow_intensity']);
    }

    public function test_guest_cannot_enable_ar_tour()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        $response = $this->postJson(route('property.ar-tour.enable', $property));

        $response->assertStatus(401);
    }

    public function test_guest_cannot_disable_ar_tour()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        $response = $this->postJson(route('property.ar-tour.disable', $property));

        $response->assertStatus(401);
    }

    public function test_guest_cannot_update_ar_tour_settings()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        $response = $this->putJson(route('property.ar-tour.update-settings', $property), [
            'auto_rotate' => false,
        ]);

        $response->assertStatus(401);
    }
}
