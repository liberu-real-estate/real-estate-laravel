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
            'start_date' => now(),
            'end_date' => now()->addDays(7),
            'status' => 'confirmed',
        ];

        $booking = Booking::create($bookingData);

        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertDatabaseHas('bookings', $bookingData);
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