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
    public $selectedAgent;
    public $availableTimeSlots;

    public function mount()
    {
        $this->dates = Property::all()->flatMap(function ($property) {
            return $property->getAvailableDates();
        })->unique();

        $this->bookings = Booking::with('property')->get();
        $this->availableTimeSlots = [];
    }

    public function selectDate($date)
    {
        $this->dates = collect($this->dates)->push($date);
        $this->updateAvailableTimeSlots($date);
    }

    public function updateAvailableTimeSlots($date)
    {
        // Fetch available time slots based on agent availability and existing appointments
        $this->availableTimeSlots = $this->selectedAgent->getAvailableTimeSlots($date);
    }

    public function bookViewing($propertyId, $date, $timeSlot)
    {
        $appointment = Appointment::create([
            'property_id' => $propertyId,
            'agent_id' => $this->selectedAgent->id,
            'user_id' => auth()->id(),
            'appointment_date' => Carbon::parse($date . ' ' . $timeSlot),
            'status' => 'requested',
        ]);

        $this->emit('viewingRequested', $appointment->id);
    }

    public function render()
    {
        return view('livewire.booking-calendar', [
            'dates' => $this->dates,
            'bookings' => $this->bookings,
            'selectedProperty' => $this->selectedProperty,
            'selectedAgent' => $this->selectedAgent,
            'availableTimeSlots' => $this->availableTimeSlots,
        ]);
    }
}
