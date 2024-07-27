<?php

namespace Tests\Unit;

use App\Models\ZooplaSettings;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ZooplaSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_zoopla_settings()
    {
        $settingsData = [
            'api_key' => 'test_api_key',
            'feed_id' => 'test_feed_id',
            'is_active' => true,
        ];

        $settings = ZooplaSettings::create($settingsData);

        $this->assertInstanceOf(ZooplaSettings::class, $settings);
        $this->assertDatabaseHas('zoopla_settings', $settingsData);
    }

    public function test_zoopla_settings_relationships()
    {
        $settings = ZooplaSettings::factory()->create();

        // Add relationship tests here if ZooplaSettings has any relationships
        // For example:
        // $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $settings->relatedModel);
    }

    public function test_zoopla_settings_scopes()
    {
        ZooplaSettings::factory()->create(['is_active' => true]);
        ZooplaSettings::factory()->create(['is_active' => false]);

        $this->assertCount(1, ZooplaSettings::where('is_active', true)->get());
        $this->assertCount(2, ZooplaSettings::all());
    }
}