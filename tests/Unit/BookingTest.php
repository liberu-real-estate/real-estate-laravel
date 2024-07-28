<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_booking()
    {
        $property = Property::factory()->create();
        $user = User::factory()->create();

        $bookingData = [
            'property_id' => $property->id,
            'user_id' => $user->id,
            'date' => now(),
            'time' => now()->format('H:i'),
            'status' => 'confirmed',
        ];

        $booking = Booking::create($bookingData);

        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertDatabaseHas('bookings', $bookingData);
    }

    public function test_cancel_booking()
    {
        $booking = Booking::factory()->create(['status' => 'confirmed']);
        $booking->cancel();

        $this->assertEquals('cancelled', $booking->fresh()->status);
    }

    public function test_reschedule_booking()
    {
        $booking = Booking::factory()->create(['status' => 'confirmed']);
        $newDate = now()->addDays(7);
        $newTime = '14:00';

        $booking->reschedule($newDate, $newTime);

        $this->assertEquals($newDate->format('Y-m-d'), $booking->fresh()->date->format('Y-m-d'));
        $this->assertEquals($newTime, $booking->fresh()->time->format('H:i'));
    }

    public function test_active_scope()
    {
        Booking::factory()->create(['status' => 'confirmed']);
        Booking::factory()->create(['status' => 'cancelled']);

        $activeBookings = Booking::active()->get();

        $this->assertCount(1, $activeBookings);
        $this->assertEquals('confirmed', $activeBookings->first()->status);
    }

    public function test_booking_relationships()
    {
        $booking = Booking::factory()->create();

        $this->assertInstanceOf(Property::class, $booking->property);
        $this->assertInstanceOf(User::class, $booking->user);
    }

    public function test_booking_scopes()
    {
        Booking::factory()->create(['status' => 'confirmed']);
        Booking::factory()->create(['status' => 'pending']);

        $this->assertCount(1, Booking::where('status', 'confirmed')->get());
        $this->assertCount(2, Booking::all());
    }
}