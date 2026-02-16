<?php

namespace Tests\Feature;

use App\Models\CommunityEvent;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_public_community_events()
    {
        CommunityEvent::factory()->count(5)->create(['is_public' => true]);
        CommunityEvent::factory()->count(2)->create(['is_public' => false]);

        $response = $this->getJson('/api/community-events');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    }

    public function test_can_view_single_community_event()
    {
        $event = CommunityEvent::factory()->create(['is_public' => true]);

        $response = $this->getJson("/api/community-events/{$event->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'title' => $event->title,
            'location' => $event->location,
        ]);
    }

    public function test_can_filter_events_by_category()
    {
        CommunityEvent::factory()->count(3)->create(['category' => 'festival', 'is_public' => true]);
        CommunityEvent::factory()->count(2)->create(['category' => 'market', 'is_public' => true]);

        $response = $this->getJson('/api/community-events?category=festival');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_property_can_get_nearby_events()
    {
        $property = Property::factory()->create([
            'latitude' => 51.5074,
            'longitude' => -0.1278,
        ]);

        // Create event near the property
        CommunityEvent::factory()->create([
            'latitude' => 51.5100,
            'longitude' => -0.1300,
            'is_public' => true,
        ]);

        // Create event far from the property
        CommunityEvent::factory()->create([
            'latitude' => 52.4862,
            'longitude' => -1.8904,
            'is_public' => true,
        ]);

        $events = $property->getNearbyCommunityEvents(10);

        $this->assertCount(1, $events);
    }

    public function test_can_get_events_for_property_via_api()
    {
        $property = Property::factory()->create([
            'latitude' => 51.5074,
            'longitude' => -0.1278,
        ]);

        CommunityEvent::factory()->create([
            'latitude' => 51.5100,
            'longitude' => -0.1300,
            'is_public' => true,
        ]);

        $response = $this->getJson("/api/properties/{$property->id}/community-events");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'title', 'event_date', 'location'],
        ]);
    }

    public function test_upcoming_scope_only_returns_future_events()
    {
        // Past event
        CommunityEvent::factory()->create([
            'event_date' => now()->subDays(5),
            'is_public' => true,
        ]);

        // Future events
        CommunityEvent::factory()->count(3)->create([
            'event_date' => now()->addDays(5),
            'is_public' => true,
        ]);

        $upcomingEvents = CommunityEvent::public()->upcoming()->get();

        $this->assertCount(3, $upcomingEvents);
    }
}
