<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Property;
use App\Models\AppointmentType;
use App\Services\NotificationService;
use App\Services\CalendarIntegrationService;
use Carbon\Carbon;

class BookingCalendar extends Component
{
    public $dates;
    public $appointments;
    public $selectedProperty;
    public $selectedAgent;
    public $availableTimeSlots;
    public $appointmentType;
    public $selectedDate;
    public $selectedTimeSlot;

    protected $notificationService;
    protected $calendarIntegrationService;

    public function boot(NotificationService $notificationService, CalendarIntegrationService $calendarIntegrationService)
    {
        $this->notificationService = $notificationService;
        $this->calendarIntegrationService = $calendarIntegrationService;
    }

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
        $this->selectedDate = $date;
        $this->updateAvailableTimeSlots($date);
    }

    public function updateAvailableTimeSlots($date)
    {
        // Fetch available time slots based on agent availability and existing appointments
        $this->availableTimeSlots = $this->selectedAgent->getAvailableTimeSlots($date);
    }

    public function selectTimeSlot($timeSlot)
    {
        $this->selectedTimeSlot = $timeSlot;
    }

    public function bookAppointment()
    {
        $this->validate([
            'selectedProperty' => 'required',
            'selectedAgent' => 'required',
            'selectedDate' => 'required|date',
            'selectedTimeSlot' => 'required',
        ]);

        $appointment = Appointment::create([
            'property_id' => $this->selectedProperty->id,
            'agent_id' => $this->selectedAgent->id,
            'user_id' => auth()->id(),
            'appointment_date' => Carbon::parse($this->selectedDate . ' ' . $this->selectedTimeSlot),
            'status' => 'requested',
            'appointment_type_id' => $this->appointmentType->id,
        ]);

        // Send notification
        $this->notificationService->notifyAppointmentCreated(auth()->user(), $appointment);

        // Sync with calendar
        $this->calendarIntegrationService->addAppointmentToCalendar($appointment);

        $this->emit('appointmentRequested', $appointment->id);
        session()->flash('message', 'Appointment scheduled successfully!');
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
