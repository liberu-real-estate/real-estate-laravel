<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Property;
use App\Models\AppointmentType;
use Carbon\Carbon;

class BookingCalendar extends Component
{
    public $dates;
    public $appointments;
    public $selectedProperty;
    public $selectedAgent;
    public $availableTimeSlots;
    public $appointmentType;

    public function mount($appointmentType)
    {
        $this->appointmentType = AppointmentType::findOrFail($appointmentType);
        $this->dates = Property::all()->flatMap(function ($property) {
            return $property->getAvailableDates();
        })->unique();

        $this->appointments = Appointment::with('property')->get();
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

    public function bookAppointment($propertyId, $date, $timeSlot)
    {
        $appointment = Appointment::create([
            'property_id' => $propertyId,
            'agent_id' => $this->selectedAgent->id,
            'user_id' => auth()->id(),
            'appointment_date' => Carbon::parse($date . ' ' . $timeSlot),
            'status' => 'requested',
            'appointment_type_id' => $this->appointmentType->id,
        ]);

        $this->emit('appointmentRequested', $appointment->id);
    }

    public function render()
    {
        return view('livewire.booking-calendar', [
            'dates' => $this->dates,
            'appointments' => $this->appointments,
            'selectedProperty' => $this->selectedProperty,
            'selectedAgent' => $this->selectedAgent,
            'availableTimeSlots' => $this->availableTimeSlots,
            'appointmentType' => $this->appointmentType,
        ]);
    }
}
