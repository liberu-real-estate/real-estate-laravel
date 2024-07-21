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
    public $userName;
    public $userContact;
    public $notes;
    public $availableDates = [];

    protected $rules = [
        'selectedDate' => 'required|date|after_or_equal:today',
        'userName' => 'required|string|max:255',
        'userContact' => 'required|string|max:255',
        'notes' => 'nullable|string|max:1000',
    ];

    public function mount($propertyId)
    {
        $this->propertyId = $propertyId;
        $this->availableDates = Property::find($this->propertyId)->getAvailableDates();
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->validate(['selectedDate' => $this->rules['selectedDate']]);
    }

    public function loadBookingCalendar()
    {
        return $this->loadComponent('BookingCalendar', ['propertyId' => $this->propertyId]);
    }

    public function bookViewing()
    {
        $this->validate();

        try {
            Booking::create([
                'property_id' => $this->propertyId,
                'date' => new Carbon($this->selectedDate),
                'user_id' => auth()->id(),
                'name' => $this->userName,
                'contact' => $this->userContact,
                'notes' => $this->notes,
            ]);

            session()->flash('message', 'Viewing scheduled successfully for ' . $this->selectedDate);
            $this->reset(['selectedDate', 'userName', 'userContact', 'notes']);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to schedule viewing. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.property-booking', [
            'availableDates' => $this->availableDates,
            'bookingCalendar' => $this->loadBookingCalendar(),
        ]);
    }
}

