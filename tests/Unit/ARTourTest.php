<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Models\User;
use App\Models\Team;
use App\Services\ARTourService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ARTourTest extends TestCase
{
    use RefreshDatabase;

    protected ARTourService $arTourService;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->arTourService = new ARTourService();
    }

    public function test_property_has_ar_tour_fields()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'ar_tour_enabled' => true,
            'ar_model_scale' => 1.5,
        ]);

        $this->assertTrue($property->ar_tour_enabled);
        $this->assertEquals(1.5, $property->ar_model_scale);
    }

    public function test_migration_adds_ar_tour_fields()
    {
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasColumn('properties', 'ar_tour_enabled'));
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasColumn('properties', 'ar_tour_settings'));
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasColumn('properties', 'ar_placement_guide'));
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasColumn('properties', 'ar_model_scale'));
    }

    public function test_property_model_includes_ar_fields_in_fillable()
    {
        $property = new Property();
        $fillable = $property->getFillable();
        
        $this->assertContains('ar_tour_enabled', $fillable);
        $this->assertContains('ar_tour_settings', $fillable);
        $this->assertContains('ar_placement_guide', $fillable);
        $this->assertContains('ar_model_scale', $fillable);
    }

    public function test_property_casts_ar_fields_correctly()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'ar_tour_enabled' => '1',
            'ar_tour_settings' => ['test' => 'value'],
            'ar_model_scale' => '2.5',
        ]);

        $this->assertIsBool($property->ar_tour_enabled);
        $this->assertIsArray($property->ar_tour_settings);
        $this->assertIsFloat($property->ar_model_scale);
    }

    public function test_ar_tour_is_not_available_without_3d_model()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'ar_tour_enabled' => true,
        ]);

        $this->assertFalse($this->arTourService->isARTourAvailable($property));
    }

    public function test_ar_tour_is_available_with_3d_model_and_enabled()
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

        $this->assertTrue($this->arTourService->isARTourAvailable($property));
    }

    public function test_ar_tour_is_not_available_when_disabled()
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

        $this->assertFalse($this->arTourService->isARTourAvailable($property));
    }

    public function test_can_enable_ar_tour_with_3d_model()
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

        $success = $this->arTourService->enableARTour($property);
        
        $this->assertTrue($success);
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

        $success = $this->arTourService->enableARTour($property);
        
        $this->assertFalse($success);
        $this->assertFalse($property->fresh()->ar_tour_enabled);
    }

    public function test_can_disable_ar_tour()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'ar_tour_enabled' => true,
        ]);

        $success = $this->arTourService->disableARTour($property);
        
        $this->assertTrue($success);
        $this->assertFalse($property->fresh()->ar_tour_enabled);
    }

    public function test_get_ar_tour_config_returns_correct_structure()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'ar_tour_enabled' => true,
            'ar_model_scale' => 2.0,
            'ar_placement_guide' => 'floor',
        ]);

        $file = UploadedFile::fake()->create('model.glb', 1024, 'model/gltf-binary');
        $property->addMedia($file)->toMediaCollection('3d_models');

        $config = $this->arTourService->getARTourConfig($property);

        $this->assertIsArray($config);
        $this->assertArrayHasKey('model_url', $config);
        $this->assertArrayHasKey('scale', $config);
        $this->assertArrayHasKey('placement_guide', $config);
        $this->assertArrayHasKey('ar_modes', $config);
        $this->assertEquals(2.0, $config['scale']);
        $this->assertEquals('floor', $config['placement_guide']);
    }

    public function test_can_update_ar_tour_settings()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'ar_tour_enabled' => true,
        ]);

        $newSettings = [
            'auto_rotate' => false,
            'shadow_intensity' => 2,
        ];

        $success = $this->arTourService->updateARTourSettings($property, $newSettings);
        
        $this->assertTrue($success);
        
        $settings = $property->fresh()->ar_tour_settings;
        $this->assertFalse($settings['auto_rotate']);
        $this->assertEquals(2, $settings['shadow_intensity']);
    }

    public function test_validate_3d_model_accepts_glb_format()
    {
        $result = $this->arTourService->validate3DModel('test.glb');
        
        $this->assertTrue($result['valid']);
    }

    public function test_validate_3d_model_accepts_gltf_format()
    {
        $result = $this->arTourService->validate3DModel('test.gltf');
        
        $this->assertTrue($result['valid']);
    }

    public function test_validate_3d_model_rejects_invalid_format()
    {
        $result = $this->arTourService->validate3DModel('test.obj');
        
        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('Unsupported', $result['message']);
    }

    public function test_get_ar_tour_stats()
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

        $stats = $this->arTourService->getARTourStats($property);
        
        $this->assertIsArray($stats);
        $this->assertTrue($stats['ar_enabled']);
        $this->assertTrue($stats['has_3d_model']);
        $this->assertTrue($stats['is_available']);
    }
}
