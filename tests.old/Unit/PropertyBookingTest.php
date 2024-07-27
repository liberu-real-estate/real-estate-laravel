<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Property;
use App\Models\Booking;
use App\Http\Livewire\PropertyBooking;
use Illuminate\Support\Facades\Auth;

class PropertyBookingTest extends TestCase
{
    use RefreshDatabase;

    public function testMountMethodLoadsAvailableDates()
    {
        $property = Property::factory()->create();
        $availableDates = ['2023-04-10', '2023-04-11'];
        Property::shouldReceive('find')->with($property->id)->andReturnSelf();
        Property::shouldReceive('getAvailableDates')->andReturn($availableDates);

        $component = \Livewire::test(PropertyBooking::class, ['propertyId' => $property->id]);

        $component->assertSet('availableDates', $availableDates);
    }

    public function testSelectDateValidatesInput()
    {
        $component = \Livewire::test(PropertyBooking::class);

        $component->call('selectDate', '2023-04-10');
        $component->assertHasNoErrors();

        $component->call('selectDate', 'invalid-date');
        $component->assertHasErrors(['selectedDate' => 'date']);
    }

    public function testBookViewingCreatesBookingAndResetsSelectedDate()
    {
        $user = User::factory()->create();
        Auth::shouldReceive('id')->andReturn($user->id);
        $property = Property::factory()->create();
        $date = '2023-04-10';

        $component = \Livewire::test(PropertyBooking::class, ['propertyId' => $property->id])
            ->set('selectedDate', $date)
            ->call('bookViewing');

        $this->assertDatabaseHas('bookings', [
            'property_id' => $property->id,
            'date' => $date,
            'user_id' => $user->id,
        ]);

        $component->assertSet('selectedDate', null);
        $component->assertSessionHas('message', 'Booking successful for ' . $date);
    }

    public function testRenderMethodProvidesAvailableDatesToView()
    {
        $availableDates = ['2023-04-10', '2023-04-11'];
        $property = Property::factory()->create();
        Property::shouldReceive('find')->with($property->id)->andReturnSelf();
        Property::shouldReceive('getAvailableDates')->andReturn($availableDates);

        $component = \Livewire::test(PropertyBooking::class, ['propertyId' => $property->id]);

        $component->assertViewHas('availableDates', $availableDates);
    }
}
