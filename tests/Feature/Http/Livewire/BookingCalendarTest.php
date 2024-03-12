<?php

namespace Tests\Feature\Http\Livewire;

use Tests\TestCase;
use App\Models\Booking;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class BookingCalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_mount_method_initializes_dates_and_bookings_correctly()
    {
        $property = Property::factory()->create();
        $booking = Booking::factory()->create();

        Livewire::test('booking-calendar')
            ->assertSet('dates', Property::all()->flatMap->getAvailableDates()->unique())
            ->assertSet('bookings', Booking::with('property')->get());
    }

    public function test_select_date_adds_date_to_dates_collection()
    {
        $date = '2023-04-01';
        Livewire::test('booking-calendar')
            ->call('selectDate', $date)
            ->assertSee($date);
    }

    public function test_book_property_creates_booking_and_emits_event()
    {
        $propertyId = Property::factory()->create()->id;
        $date = '2023-04-02';

        Livewire::test('booking-calendar')
            ->call('bookProperty', $propertyId, $date)
            ->assertHasNoErrors()
            ->assertDatabaseHas('bookings', [
                'property_id' => $propertyId,
                'date' => $date,
                'user_id' => auth()->id(),
            ])
            ->assertEmitted('bookingSuccessful');
    }
}
