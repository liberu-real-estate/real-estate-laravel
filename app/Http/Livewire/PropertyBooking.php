<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\Booking;
use Carbon\Carbon;

class PropertyBooking extends Component
{
    public $propertyId;
    public $selectedDate;
    public $availableDates = [];

    protected $rules = [
        'selectedDate' => 'required|date|after_or_equal:today',
    ];

    public function mount($propertyId)
    {
        $this->propertyId = $propertyId;
        // Assuming a method exists in the Property model to get available dates
        $this->availableDates = Property::find($this->propertyId)->getAvailableDates();
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->validate();
    }

    public function bookViewing()
    public function loadBookingCalendar()
    {
        return $this->loadComponent('BookingCalendar', ['propertyId' => $this->propertyId]);
    }
    {
        $this->validate();

        Booking::create([
            'property_id' => $this->propertyId,
            'date' => new Carbon($this->selectedDate),
            'user_id' => auth()->id(),
        ]);

        session()->flash('message', 'Booking successful for ' . $this->selectedDate);
        $this->reset('selectedDate');
    }

    public function render()
    {
        return view('livewire.property-booking', [
            'availableDates' => $this->availableDates,
            'bookingCalendar' => $this->loadBookingCalendar(),
        ]);
    }
}
}
