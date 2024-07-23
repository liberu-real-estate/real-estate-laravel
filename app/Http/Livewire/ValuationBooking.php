<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AppointmentType;

class ValuationBooking extends Component
{
    public $selectedDate;
    public $userName;
    public $userContact;
    public $notes;

    public function mount()
    {
        $this->appointmentType = AppointmentType::where('name', 'Valuation')->firstOrFail();
    }

    public function bookValuation()
    {
        $this->validate([
            'selectedDate' => 'required|date',
            'userName' => 'required|string',
            'userContact' => 'required|string',
        ]);

        // Logic to book the valuation appointment
        // This will be similar to the bookAppointment method in BookingCalendar

        session()->flash('message', 'Valuation appointment requested successfully!');
    }

    public function render()
    {
        return view('livewire.valuation-booking', [
            'appointmentType' => $this->appointmentType,
        ]);
    }
}