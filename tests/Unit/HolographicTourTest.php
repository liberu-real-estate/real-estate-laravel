<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Models\User;
use App\Models\Team;
use App\Services\HolographicTourService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class HolographicTourTest extends TestCase
{
    use RefreshDatabase;

    protected HolographicTourService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new HolographicTourService();
    }

    public function test_migration_adds_holographic_fields_to_properties()
    {
        $this->assertTrue(
            \Illuminate\Support\Facades\Schema::hasColumn('properties', 'holographic_tour_url')
        );
        $this->assertTrue(
            \Illuminate\Support\Facades\Schema::hasColumn('properties', 'holographic_provider')
        );
        $this->assertTrue(
            \Illuminate\Support\Facades\Schema::hasColumn('properties', 'holographic_metadata')
        );
        $this->assertTrue(
            \Illuminate\Support\Facades\Schema::hasColumn('properties', 'holographic_enabled')
        );
    }

    public function test_property_model_includes_holographic_fields_in_fillable()
    {
        $property = new Property();
        $fillable = $property->getFillable();
        
        $this->assertContains('holographic_tour_url', $fillable);
        $this->assertContains('holographic_provider', $fillable);
        $this->assertContains('holographic_metadata', $fillable);
        $this->assertContains('holographic_enabled', $fillable);
    }

    public function test_property_can_check_if_has_holographic_tour()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'holographic_enabled' => false,
            'holographic_tour_url' => null,
        ]);

        // Initially should not have holographic tour
        $this->assertFalse($property->hasHolographicTour());

        // Enable holographic tour
        $property->update([
            'holographic_enabled' => true,
            'holographic_tour_url' => 'https://example.com/holographic-tour/1',
        ]);

        // Now should have holographic tour
        $this->assertTrue($property->hasHolographicTour());
    }

    public function test_can_generate_holographic_tour_data()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'model_3d_url' => 'https://example.com/model.glb',
        ]);

        $tourData = $this->service->generateHolographicTour($property);

        $this->assertNotNull($tourData);
        $this->assertIsArray($tourData);
        $this->assertEquals($property->id, $tourData['property_id']);
        $this->assertArrayHasKey('model_url', $tourData);
        $this->assertArrayHasKey('display_type', $tourData);
        $this->assertEquals('hologram', $tourData['display_type']);
    }

    public function test_returns_null_when_no_3d_model_available()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'model_3d_url' => null,
        ]);

        $tourData = $this->service->generateHolographicTour($property);

        $this->assertNull($tourData);
    }

    public function test_can_check_if_holographic_tour_is_available()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'holographic_enabled' => true,
            'holographic_tour_url' => 'https://example.com/holographic-tour/1',
        ]);

        $this->assertTrue($this->service->isAvailable($property));
    }

    public function test_returns_supported_devices_list()
    {
        $devices = $this->service->getSupportedDevices();

        $this->assertIsArray($devices);
        $this->assertArrayHasKey('looking_glass', $devices);
        $this->assertArrayHasKey('web_viewer', $devices);
        $this->assertArrayHasKey('name', $devices['looking_glass']);
        $this->assertArrayHasKey('resolution', $devices['looking_glass']);
    }

    public function test_can_validate_holographic_content()
    {
        $validMetadata = [
            'property_id' => 1,
            'model_url' => 'https://example.com/model.glb',
            'display_type' => 'hologram',
        ];

        $this->assertTrue($this->service->validateContent($validMetadata));

        $invalidMetadata = [
            'property_id' => 1,
            // Missing model_url and display_type
        ];

        $this->assertFalse($this->service->validateContent($invalidMetadata));
    }

    public function test_can_get_holographic_metadata()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $metadata = [
            'property_id' => 1,
            'model_url' => 'https://example.com/model.glb',
            'display_type' => 'hologram',
            'resolution' => '4k',
        ];
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'holographic_metadata' => $metadata,
        ]);

        $retrievedMetadata = $this->service->getMetadata($property);

        $this->assertNotNull($retrievedMetadata);
        $this->assertEquals($metadata['property_id'], $retrievedMetadata['property_id']);
        $this->assertEquals($metadata['display_type'], $retrievedMetadata['display_type']);
    }

    public function test_can_update_holographic_configuration()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'holographic_metadata' => ['initial' => 'data'],
        ]);

        $newConfig = [
            'resolution' => '8k',
            'viewing_angles' => ['front', 'back'],
        ];

        $result = $this->service->updateConfiguration($property, $newConfig);

        $this->assertTrue($result);
        $property->refresh();
        $this->assertArrayHasKey('resolution', $property->holographic_metadata);
        $this->assertEquals('8k', $property->holographic_metadata['resolution']);
    }

    public function test_can_disable_holographic_tour()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'holographic_enabled' => true,
            'holographic_tour_url' => 'https://example.com/tour',
        ]);

        $result = $this->service->disable($property);

        $this->assertTrue($result);
        $property->refresh();
        $this->assertFalse($property->holographic_enabled);
    }

    public function test_holographic_tour_data_is_cached()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'model_3d_url' => 'https://example.com/model.glb',
        ]);

        // Generate tour data (should cache it)
        $this->service->generateHolographicTour($property);

        // Check cache exists
        $cacheKey = "holographic_tour_{$property->id}";
        $this->assertTrue(Cache::has($cacheKey));

        // Get cached data
        $cachedData = Cache::get($cacheKey);
        $this->assertNotNull($cachedData);
        $this->assertEquals($property->id, $cachedData['property_id']);
    }
}
