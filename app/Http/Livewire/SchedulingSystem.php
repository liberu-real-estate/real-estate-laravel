<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\Appointment;
use App\Models\AppointmentType;
use App\Services\NotificationService;
use App\Services\CalendarIntegrationService;
use Carbon\Carbon;

class SchedulingSystem extends Component
{
    public $properties;
    public $agents;
    public $appointmentTypes;
    public $selectedProperty;
    public $selectedAgent;
    public $selectedAppointmentType;
    public $selectedDate;
    public $selectedTimeSlot;
    public $availableTimeSlots = [];

    protected $notificationService;
    protected $calendarIntegrationService;

    protected $rules = [
        'selectedProperty' => 'required',
        'selectedAgent' => 'required',
        'selectedAppointmentType' => 'required',
        'selectedDate' => 'required|date',
        'selectedTimeSlot' => 'required',
    ];

    public function boot(NotificationService $notificationService, CalendarIntegrationService $calendarIntegrationService)
    {
        $this->notificationService = $notificationService;
        $this->calendarIntegrationService = $calendarIntegrationService;
    }

    public function mount()
    {
        $this->properties = Property::all();
        $this->agents = User::role('agent')->get();
        $this->appointmentTypes = AppointmentType::all();
    }

    public function updatedSelectedProperty()
    {
        $this->resetAvailability();
    }

    public function updatedSelectedAgent()
    {
        $this->resetAvailability();
    }

    public function updatedSelectedDate()
    {
        $this->updateAvailableTimeSlots();
    }

    public function resetAvailability()
    {
        $this->selectedDate = null;
        $this->selectedTimeSlot = null;
        $this->availableTimeSlots = [];
    }

    public function updateAvailableTimeSlots()
    {
        if ($this->selectedAgent && $this->selectedDate) {
            $this->availableTimeSlots = $this->selectedAgent->getAvailableTimeSlots($this->selectedDate);
        }
    }

    public function scheduleAppointment()
    {
        $this->validate();

        $appointment = Appointment::create([
            'property_id' => $this->selectedProperty->id,
            'agent_id' => $this->selectedAgent->id,
            'user_id' => auth()->id(),
            'appointment_date' => Carbon::parse($this->selectedDate . ' ' . $this->selectedTimeSlot),
            'status' => 'scheduled',
            'appointment_type_id' => $this->selectedAppointmentType->id,
        ]);

        // Send notification
        $this->notificationService->notifyAppointmentCreated(auth()->user(), $appointment);

        // Sync with calendar
        $this->calendarIntegrationService->addAppointmentToCalendar($appointment);

        session()->flash('message', 'Appointment scheduled successfully!');
        $this->resetAvailability();
    }

    public function render()
    {
        return view('livewire.scheduling-system');
    }
}