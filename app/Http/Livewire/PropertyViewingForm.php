<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Property;
use App\Models\User;
use App\Notifications\ViewingRequestNotification;

class PropertyViewingForm extends Component
{
    public $property;
    public $selectedDate;
    public $selectedTimeSlot;
    public $availableTimeSlots = [];
    public $agents = [];

    public function mount(Property $property)
    {
        $this->property = $property;
        $this->agents = User::where('role', 'agent')->get();
    }

    public function updatedSelectedDate($value)
    {
        $this->availableTimeSlots = Appointment::getAvailableTimeSlots($value, $this->property->agent_id);
    }

    public function scheduleViewing()
    {
        $this->validate([
            'selectedDate' => 'required|date',
            'selectedTimeSlot' => 'required',
        ]);

        $appointment = Appointment::create([
            'property_id' => $this->property->id,
            'agent_id' => $this->property->agent_id,
            'user_id' => auth()->id(),
            'appointment_date' => $this->selectedDate . ' ' . $this->selectedTimeSlot,
            'status' => 'requested',
        ]);

        $agent = User::find($this->property->agent_id);
        $agent->notify(new ViewingRequestNotification($appointment));

        $this->emit('viewingScheduled', $appointment->id);
    }

    public function render()
    {
        return view('livewire.property-viewing-form');
    }
}