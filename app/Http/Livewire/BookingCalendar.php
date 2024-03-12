<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Booking;
use App\Models\Property;
use Carbon\Carbon;

class BookingCalendar extends Component
{
    public $dates;
    public $bookings;
    public $selectedProperty;

    public function mount()
    {
        $this->dates = Property::all()->flatMap(function ($property) {
            return $property->getAvailableDates();
        })->unique();

        $this->bookings = Booking::with('property')->get();
    }

    public function selectDate($date)
    {
        $this->dates = collect($this->dates)->push($date);
    }

    public function bookProperty($propertyId, $date)
/**
 * Adds a selected date to the dates collection.
 *
 * @param mixed $date The date to add.
 */
    {
        $this->dates = collect($this->dates)->push($date);
    }

    public function bookProperty($propertyId, $date)
    {
        $booking = Booking::create([
            'property_id' => $propertyId,
            'date' => new Carbon($date),
            'user_id' => auth()->id(),
        ]);

        $this->bookings->push($booking);
        $this->emit('bookingSuccessful', $booking->id);
    }

    public function render()
    {
        return view('livewire.booking-calendar', [
            'dates' => $this->dates,
            'bookings' => $this->bookings,
            'selectedProperty' => $this->selectedProperty,
        ]);
    }
}
/**
 * Initializes the component state with available dates and bookings.
 */
/**
 * Creates a booking for a property on a given date.
 *
 * @param int $propertyId The ID of the property to book.
 * @param string $date The date on which to book the property.
 */
/**
 * Renders the Livewire component.
 *
 * @return \Illuminate\View\View The view to render.
 */
