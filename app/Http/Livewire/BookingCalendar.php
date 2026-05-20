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
    public $currentMonth;
    public $currentYear;
    public $calendarDays = [];

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
        $this->currentMonth = Carbon::now()->month;
        $this->currentYear = Carbon::now()->year;

        $this->dates = Property::all()->flatMap(function ($property) {
            return $property->getAvailableDates();
        })->unique()->values()->toArray();

        $this->appointments = Appointment::with('property')
            ->where('appointment_type_id', $this->appointmentType->id)
            ->upcoming()
            ->get();

        $this->availableTimeSlots = [];
        $this->buildCalendarDays();
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->buildCalendarDays();
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->buildCalendarDays();
    }

    private function buildCalendarDays()
    {
        $firstDay = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $daysInMonth = $firstDay->daysInMonth;
        $startDow = $firstDay->dayOfWeek;
        $today = Carbon::today();

        $days = [];
        for ($i = 0; $i < $startDow; $i++) {
            $days[] = null;
        }
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = Carbon::create($this->currentYear, $this->currentMonth, $d);
            $days[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $d,
                'isPast' => $date->lt($today),
                'isToday' => $date->isToday(),
                'isWeekend' => $date->isWeekend(),
                'isAvailable' => !$date->isWeekend() && $date->gte($today),
            ];
        }
        $this->calendarDays = $days;
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->selectedTimeSlot = null;
        $this->updateAvailableTimeSlots($date);
    }

    public function updateAvailableTimeSlots($date)
    {
        $workingHours = [
            '09:00', '10:00', '11:00', '12:00', '13:00',
            '14:00', '15:00', '16:00', '17:00',
        ];

        $bookedSlots = Appointment::whereDate('appointment_date', $date)
            ->where('appointment_type_id', $this->appointmentType->id)
            ->whereNotIn('status', ['cancelled'])
            ->pluck('appointment_date')
            ->map(fn($dt) => Carbon::parse($dt)->format('H:i'))
            ->toArray();

        $this->availableTimeSlots = array_values(array_diff($workingHours, $bookedSlots));
    }

    public function selectTimeSlot($timeSlot)
    {
        $this->selectedTimeSlot = $timeSlot;
    }

    public function bookAppointment()
    {
        $this->validate([
            'selectedDate' => 'required|date|after_or_equal:today',
            'selectedTimeSlot' => 'required',
        ]);

        $appointment = Appointment::create([
            'user_id' => auth()->id(),
            'appointment_date' => Carbon::parse($this->selectedDate . ' ' . $this->selectedTimeSlot),
            'status' => 'requested',
            'appointment_type_id' => $this->appointmentType->id,
        ]);

        $this->notificationService->notifyAppointmentCreated(auth()->user(), $appointment);

        $this->emit('appointmentRequested', $appointment->getKey());
        session()->flash('message', 'Appointment scheduled successfully!');

        $this->selectedDate = null;
        $this->selectedTimeSlot = null;
        $this->availableTimeSlots = [];
    }

    public function render()
    {
        return view('livewire.booking-calendar', [
            'dates' => $this->dates,
            'appointments' => $this->appointments,
            'availableTimeSlots' => $this->availableTimeSlots,
            'appointmentType' => $this->appointmentType,
            'calendarDays' => $this->calendarDays,
            'currentMonth' => $this->currentMonth,
            'currentYear' => $this->currentYear,
        ]);
    }
}
