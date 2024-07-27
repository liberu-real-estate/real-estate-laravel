<?php

namespace Tests\Unit;

use App\Models\RightMoveSettings;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RightMoveSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_right_move_settings()
    {
        $settingsData = [
            'api_key' => 'test_api_key',
            'branch_id' => 'test_branch_id',
            'channel' => 'test_channel',
        ];

        $settings = RightMoveSettings::create($settingsData);

        $this->assertInstanceOf(RightMoveSettings::class, $settings);
        $this->assertDatabaseHas('right_move_settings', $settingsData);
    }

    public function test_right_move_settings_relationships()
    {
        $settings = RightMoveSettings::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $settings->properties);
    }
}