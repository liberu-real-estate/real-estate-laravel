<?php

namespace Tests\Unit;

use App\Models\RightMoveSettings;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RightMoveSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_right_move_settings_channel_validation()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        RightMoveSettings::create([
            'api_key' => 'test_api_key',
            'branch_id' => 'test_branch_id',
            'channel' => 'invalid_channel',
            'is_active' => true,
        ]);
    }

    public function test_right_move_settings_feed_type_validation()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        RightMoveSettings::create([
            'api_key' => 'test_api_key',
            'branch_id' => 'test_branch_id',
            'channel' => 'sales',
            'feed_type' => 'invalid_feed_type',
            'is_active' => true,
        ]);
    }

    public function test_create_right_move_settings()
    {
        $settingsData = [
            'api_key' => 'test_api_key',
            'branch_id' => 'test_branch_id',
            'channel' => 'sales',
            'feed_type' => 'incremental',
        ];

        $settings = RightMoveSettings::create($settingsData);

        $this->assertInstanceOf(RightMoveSettings::class, $settings);
        $this->assertDatabaseHas('right_move_settings', $settingsData);
    }

    public function test_right_move_settings_relationships()
    {
        $settings = RightMoveSettings::factory()->create();

        $this->assertInstanceOf(\App\Models\Branch::class, $settings->branch);
    }

    public function test_right_move_settings_is_active_default_value()
    {
        $settings = RightMoveSettings::factory()->create(['is_active' => null]);
        $this->assertTrue($settings->is_active);
    }

    public function test_right_move_settings_scope_active()
    {
        RightMoveSettings::factory()->create(['is_active' => true]);
        RightMoveSettings::factory()->create(['is_active' => false]);

        $activeSettings = RightMoveSettings::active()->get();

        $this->assertCount(1, $activeSettings);
        $this->assertTrue($activeSettings->first()->is_active);
    }
}