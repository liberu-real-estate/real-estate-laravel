<?php

namespace Tests\Feature;

use App\Models\CommunityEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityEventApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_public_upcoming_events(): void
    {
        CommunityEvent::factory()->count(3)->create([
            'is_public' => true,
            'event_date' => now()->addDays(5),
        ]);
        CommunityEvent::factory()->create([
            'is_public' => false,
            'event_date' => now()->addDays(5),
        ]);

        $response = $this->getJson('/api/community-events');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_returns_paginated_results(): void
    {
        CommunityEvent::factory()->count(5)->create([
            'is_public' => true,
            'event_date' => now()->addDays(5),
        ]);

        $response = $this->getJson('/api/community-events');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'current_page', 'last_page', 'per_page', 'total']);
    }

    public function test_can_show_public_event(): void
    {
        $event = CommunityEvent::factory()->create([
            'is_public' => true,
            'event_date' => now()->addDays(5),
        ]);

        $response = $this->getJson("/api/community-events/{$event->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('id', $event->id);
    }

    public function test_private_event_requires_auth(): void
    {
        $event = CommunityEvent::factory()->create([
            'is_public' => false,
            'event_date' => now()->addDays(5),
        ]);

        $response = $this->getJson("/api/community-events/{$event->id}");

        $response->assertStatus(403);
    }

    public function test_can_filter_events_by_category(): void
    {
        CommunityEvent::factory()->create([
            'is_public' => true,
            'event_date' => now()->addDays(5),
            'category' => 'open_house',
        ]);
        CommunityEvent::factory()->create([
            'is_public' => true,
            'event_date' => now()->addDays(5),
            'category' => 'community_meeting',
        ]);

        $response = $this->getJson('/api/community-events?category=open_house');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    public function test_returns_404_for_nonexistent_event(): void
    {
        $response = $this->getJson('/api/community-events/99999');

        $response->assertStatus(404);
    }
}
