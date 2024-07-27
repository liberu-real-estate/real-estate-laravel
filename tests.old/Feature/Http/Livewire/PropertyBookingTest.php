<?php

namespace Tests\Feature\Http\Livewire;

use Tests\TestCase;
use App\Models\Property;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class PropertyBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_mount_method_initializes_available_dates_correctly()
    {
        $property = Property::factory()->create();
        Livewire::test('property-booking', ['propertyId' => $property->id])
            ->assertSet('availableDates', $property->getAvailableDates());
    }

    public function test_select_date_sets_selected_date_and_validates_it()
    {
        $property = Property::factory()->create();
        $date = now()->addDay()->toDateString();
        Livewire::test('property-booking', ['propertyId' => $property->id])
            ->call('selectDate', $date)
            ->assertSet('selectedDate', $date)
            ->assertHasNoErrors();

        $invalidDate = now()->subDay()->toDateString();
        Livewire::test('property-booking', ['propertyId' => $property->id])
            ->call('selectDate', $invalidDate)
            ->assertHasErrors(['selectedDate' => 'after_or_equal']);
    }

    public function test_book_viewing_creates_booking_and_resets_selected_date()
    {
        $property = Property::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user);
        $date = now()->addDay()->toDateString();

        Livewire::test('property-booking', ['propertyId' => $property->id])
            ->set('selectedDate', $date)
            ->call('bookViewing')
            ->assertHasNoErrors()
            ->assertDatabaseHas('bookings', [
                'property_id' => $property->id,
                'date' => $date,
                'user_id' => $user->id,
            ])
            ->assertSet('selectedDate', null);
    }

    public function test_load_booking_calendar_loads_component_correctly()
    {
        $property = Property::factory()->create();
        Livewire::test('property-booking', ['propertyId' => $property->id])
            ->call('loadBookingCalendar')
            ->assertViewHas('bookingCalendar', function ($viewData) use ($property) {
                return $viewData['propertyId'] === $property->id;
            });
    }
}
