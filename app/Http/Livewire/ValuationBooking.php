<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\Appointment;

class ValuationBooking extends Component
{
    public $selectedDate;
    public $userName;
    public $userContact;
    public $notes;
    public $propertyAddress;

    protected $rules = [
        'selectedDate' => 'required|date',
        'userName' => 'required|string',
        'userContact' => 'required|string',
        'propertyAddress' => 'required|string',
    ];

    public function bookValuation()
    {
        $this->validate();

        $appointment = Appointment::create([
            'user_name' => $this->userName,
            'user_contact' => $this->userContact,
            'appointment_date' => $this->selectedDate,
            'notes' => $this->notes,
            'property_address' => $this->propertyAddress,
            'type' => 'valuation',
            'status' => 'requested',
        ]);

        session()->flash('message', 'Valuation appointment requested successfully!');
        $this->reset(['selectedDate', 'userName', 'userContact', 'notes', 'propertyAddress']);
    }

    public function render()
    {
        return view('livewire.valuation-booking', [
            'availableDates' => Property::getAvailableDates(),
        ]);
    }
}