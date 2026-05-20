<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\JupixApiService;
use App\Services\JupixPortalSyncService;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class JupixIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $jupixApiService;
    protected $jupixPortalSyncService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->jupixApiService = new JupixApiService();
        $this->jupixPortalSyncService = new JupixPortalSyncService($this->jupixApiService);
    }

    public function testGetProperties()
    {
        Http::fake([
            config('services.jupix.base_url') . '/properties' => Http::response([
                ['id' => 1, 'title' => 'Test Property'],
            ], 200),
        ]);

        $properties = $this->jupixApiService->getProperties();
        $this->assertIsArray($properties);
        $this->assertCount(1, $properties);
        $this->assertEquals('Test Property', $properties[0]['title']);
    }

    public function testSyncProperties()
    {
        Http::fake([
            config('services.jupix.base_url') . '/properties' => Http::response([
                [
                    'id' => 1,
                    'title' => 'Test Property',
                    'description' => 'A test property',
                    'address' => '123 Test St',
                    'price' => 200000,
                    'bedrooms' => 3,
                    'bathrooms' => 2,
                    'area' => 1500,
                    'type' => 'House',
                    'status' => 'For Sale',
                ],
            ], 200),
        ]);

        $result = $this->jupixPortalSyncService->syncProperties();

        $this->assertEquals(1, $result['synced']);
        $this->assertEquals(0, $result['failed']);

        $this->assertDatabaseHas('properties', [
            'jupix_id' => 1,
            'title' => 'Test Property',
            'description' => 'A test property',
            'location' => '123 Test St',
            'price' => 200000,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'area_sqft' => 1500,
            'property_type' => 'House',
            'status' => 'For Sale',
        ]);
    }
}