<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Property;
use App\Models\User;
use App\Models\Team;
use App\Models\AppointmentType;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Http\Livewire\PropertyDetail;

class VirtualTourTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create default team
        $this->team = Team::factory()->create();
        
        // Create a user
        $this->user = User::factory()->create();
        $this->user->teams()->attach($this->team);
    }

    public function test_property_detail_displays_virtual_tour_section()
    {
        $property = Property::factory()->create([
            'virtual_tour_url' => 'https://my.matterport.com/show/?m=example123',
            'team_id' => $this->team->id,
        ]);

        $component = Livewire::test(PropertyDetail::class, ['propertyId' => $property->id]);

        $component->assertSee('View Virtual Tour');
    }

    public function test_property_detail_hides_virtual_tour_when_not_available()
    {
        $property = Property::factory()->create([
            'virtual_tour_url' => null,
            'virtual_tour_embed_code' => null,
            'team_id' => $this->team->id,
        ]);

        $component = Livewire::test(PropertyDetail::class, ['propertyId' => $property->id]);

        $component->assertDontSee('View Virtual Tour');
    }

    public function test_toggle_virtual_tour_display()
    {
        $property = Property::factory()->create([
            'virtual_tour_url' => 'https://my.matterport.com/show/?m=example123',
            'team_id' => $this->team->id,
        ]);

        $component = Livewire::test(PropertyDetail::class, ['propertyId' => $property->id]);

        $this->assertFalse($component->get('showVirtualTour'));

        $component->call('toggleVirtualTour');

        $this->assertTrue($component->get('showVirtualTour'));

        $component->call('toggleVirtualTour');

        $this->assertFalse($component->get('showVirtualTour'));
    }

    public function test_schedule_live_tour_button_visible_when_available()
    {
        $property = Property::factory()->create([
            'virtual_tour_url' => 'https://my.matterport.com/show/?m=example123',
            'live_tour_available' => true,
            'team_id' => $this->team->id,
        ]);

        $component = Livewire::test(PropertyDetail::class, ['propertyId' => $property->id]);

        $component->assertSee('Schedule Live Tour');
    }

    public function test_schedule_live_tour_button_hidden_when_not_available()
    {
        $property = Property::factory()->create([
            'virtual_tour_url' => 'https://my.matterport.com/show/?m=example123',
            'live_tour_available' => false,
            'team_id' => $this->team->id,
        ]);

        $component = Livewire::test(PropertyDetail::class, ['propertyId' => $property->id]);

        $component->assertDontSee('Schedule Live Tour');
    }

    public function test_open_schedule_live_tour_modal()
    {
        $this->actingAs($this->user);

        $property = Property::factory()->create([
            'live_tour_available' => true,
            'team_id' => $this->team->id,
        ]);

        $component = Livewire::test(PropertyDetail::class, ['propertyId' => $property->id]);

        $this->assertFalse($component->get('showScheduleLiveTourModal'));

        $component->call('openScheduleLiveTourModal');

        $this->assertTrue($component->get('showScheduleLiveTourModal'));
    }

    public function test_close_schedule_live_tour_modal()
    {
        $this->actingAs($this->user);

        $property = Property::factory()->create([
            'live_tour_available' => true,
            'team_id' => $this->team->id,
        ]);

        $component = Livewire::test(PropertyDetail::class, ['propertyId' => $property->id]);

        $component->set('showScheduleLiveTourModal', true);
        $component->set('tourDate', '2026-03-01');
        $component->set('tourTime', '14:00');
        $component->set('tourNotes', 'Test notes');

        $component->call('closeScheduleLiveTourModal');

        $this->assertFalse($component->get('showScheduleLiveTourModal'));
        $this->assertNull($component->get('tourDate'));
        $this->assertNull($component->get('tourTime'));
        $this->assertNull($component->get('tourNotes'));
    }

    public function test_schedule_live_tour_creates_appointment()
    {
        $this->actingAs($this->user);

        $property = Property::factory()->create([
            'live_tour_available' => true,
            'team_id' => $this->team->id,
        ]);

        $component = Livewire::test(PropertyDetail::class, ['propertyId' => $property->id]);

        $component->set('tourDate', now()->addDays(2)->format('Y-m-d'));
        $component->set('tourTime', '14:00');
        $component->set('tourNotes', 'I am interested in the property');

        $component->call('scheduleLiveTour');

        $this->assertDatabaseHas('appointments', [
            'user_id' => $this->user->id,
            'property_id' => $property->id,
            'status' => 'scheduled',
        ]);

        // Check that appointment type was created
        $this->assertDatabaseHas('appointment_types', [
            'name' => 'Live Virtual Tour',
        ]);
    }

    public function test_schedule_live_tour_validation()
    {
        $this->actingAs($this->user);

        $property = Property::factory()->create([
            'live_tour_available' => true,
            'team_id' => $this->team->id,
        ]);

        $component = Livewire::test(PropertyDetail::class, ['propertyId' => $property->id]);

        // Test without date
        $component->set('tourDate', '');
        $component->set('tourTime', '14:00');
        $component->call('scheduleLiveTour')
            ->assertHasErrors(['tourDate']);

        // Test without time
        $component->set('tourDate', now()->addDays(2)->format('Y-m-d'));
        $component->set('tourTime', '');
        $component->call('scheduleLiveTour')
            ->assertHasErrors(['tourTime']);

        // Test with past date
        $component->set('tourDate', now()->subDays(1)->format('Y-m-d'));
        $component->set('tourTime', '14:00');
        $component->call('scheduleLiveTour')
            ->assertHasErrors(['tourDate']);
    }

    public function test_schedule_live_tour_requires_authentication()
    {
        $property = Property::factory()->create([
            'live_tour_available' => true,
            'team_id' => $this->team->id,
        ]);

        $component = Livewire::test(PropertyDetail::class, ['propertyId' => $property->id]);

        $component->call('openScheduleLiveTourModal');

        // Should redirect to login
        $this->assertGuest();
    }

    public function test_scheduled_tour_shows_success_message()
    {
        $this->actingAs($this->user);

        $property = Property::factory()->create([
            'live_tour_available' => true,
            'team_id' => $this->team->id,
        ]);

        $component = Livewire::test(PropertyDetail::class, ['propertyId' => $property->id]);

        $component->set('tourDate', now()->addDays(2)->format('Y-m-d'));
        $component->set('tourTime', '14:00');
        $component->set('tourNotes', 'Test notes');

        $component->call('scheduleLiveTour');

        $component->assertSet('showScheduleLiveTourModal', false);
        $component->assertEmitted('tourScheduled');
    }
}
