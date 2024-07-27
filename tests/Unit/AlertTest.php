<?php

namespace Tests\Unit;

use App\Models\Alert;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlertTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_alert()
    {
        $alertData = [
            'user_id' => 1,
            'type' => 'price_change',
            'criteria' => json_encode(['property_id' => 1, 'price_threshold' => 200000]),
            'frequency' => 'daily',
        ];

        $alert = Alert::create($alertData);

        $this->assertInstanceOf(Alert::class, $alert);
        $this->assertDatabaseHas('alerts', $alertData);
    }

    public function test_alert_relationships()
    {
        $alert = Alert::factory()->create();

        $this->assertInstanceOf(\App\Models\User::class, $alert->user);
    }
}