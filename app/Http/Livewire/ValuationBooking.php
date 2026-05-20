<?php

namespace App\Http\Livewire;

use Exception;
use Livewire\Component;
use App\Models\AppointmentType;
use App\Models\Appointment;
use App\Models\User;
use App\Services\CalendarIntegrationService;
use Carbon\Carbon;

class ValuationBooking extends Component
{
    public $selectedDate;
    public $selectedTime;
    public $userName;
    public $userEmail;
    public $userContact;
    public $notes;
    public $propertyAddress;
    public $propertyType;
    public $areaSqft;
    public $bedrooms;
    public $bathrooms;
    public $availableDates = [];
    public $availableTimeSlots = [];
    public $appointmentType;
    public $bookingConfirmed = false;
    public $confirmedAppointmentId = null;
    public $googleCalendarUrl = null;
    public $outlookCalendarUrl = null;

    protected $rules = [
        'selectedDate' => 'required|date|after_or_equal:today',
        'selectedTime' => 'required|string',
        'userName' => 'required|string|max:255',
        'userEmail' => 'nullable|email|max:255',
        'userContact' => 'required|string|max:255',
        'propertyAddress' => 'required|string|max:500',
        'propertyType' => 'required|string|in:house,apartment,condo,townhouse,land,commercial,other',
        'areaSqft' => 'nullable|integer|min:1',
        'bedrooms' => 'nullable|integer|min:0',
        'bathrooms' => 'nullable|integer|min:0',
        'notes' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->appointmentType = AppointmentType::where('name', 'Valuation')->firstOrFail();
        $this->availableDates = $this->getAvailableDates();
    }

    private function getAvailableDates()
    {
        $startDate = Carbon::today()->addDay();
        $endDate = Carbon::today()->addDays(30);
        $dates = [];
        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            if (!$current->isWeekend()) {
                $dates[] = $current->format('Y-m-d');
            }
            $current->addDay();
        }
        return $dates;
    }

    public function updatedSelectedDate($value)
    {
        $this->selectedTime = null;
        if ($value) {
            $this->availableTimeSlots = $this->getAvailableTimeSlots($value);
        } else {
            $this->availableTimeSlots = [];
        }
    }

    private function getAvailableTimeSlots($date)
    {
        $workingHours = [
            '09:00', '10:00', '11:00', '12:00', '13:00',
            '14:00', '15:00', '16:00', '17:00',
        ];

        $bookedSlots = Appointment::whereDate('appointment_date', $date)
            ->where('appointment_type_id', $this->appointmentType->id)
            ->whereNotIn('status', ['cancelled'])
            ->pluck('appointment_date')
            ->map(fn($dateTime) => Carbon::parse($dateTime)->format('H:i'))
            ->toArray();

        return array_values(array_diff($workingHours, $bookedSlots));
    }

    public function bookValuation()
    {
        $this->validate();

        try {
            if (!in_array($this->selectedTime, $this->getAvailableTimeSlots($this->selectedDate))) {
                throw new Exception('Selected time slot is no longer available.');
            }

            $defaultStaffId = User::role('staff')->first()->id ?? null;

            $appointment = Appointment::create([
                'appointment_type_id' => $this->appointmentType->id,
                'appointment_date' => Carbon::parse($this->selectedDate . ' ' . $this->selectedTime),
                'user_id' => auth()->id(),
                'staff_id' => $defaultStaffId,
                'name' => $this->userName,
                'contact' => $this->userContact,
                'notes' => $this->notes,
                'status' => 'requested',
                'property_address' => $this->propertyAddress,
                'property_type' => $this->propertyType,
                'area_sqft' => $this->areaSqft,
                'bedrooms' => $this->bedrooms,
                'bathrooms' => $this->bathrooms,
            ]);

            $calendarService = app(CalendarIntegrationService::class);
            $this->googleCalendarUrl = $calendarService->getAppointmentGoogleCalendarUrl($appointment);
            $this->outlookCalendarUrl = $calendarService->getAppointmentOutlookCalendarUrl($appointment);
            $this->confirmedAppointmentId = $appointment->getKey();
            $this->bookingConfirmed = true;

            session()->flash('message', 'Valuation appointment requested for ' . Carbon::parse($this->selectedDate)->format('F j, Y') . ' at ' . $this->selectedTime);
            $this->reset(['selectedDate', 'selectedTime', 'userName', 'userEmail', 'userContact', 'notes', 'propertyAddress', 'propertyType', 'areaSqft', 'bedrooms', 'bathrooms', 'availableTimeSlots']);
        } catch (Exception $e) {
            session()->flash('error', 'Failed to book appointment: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.valuation-booking', [
            'appointmentType' => $this->appointmentType,
            'availableDates' => $this->availableDates,
            'availableTimeSlots' => $this->availableTimeSlots,
        ]);
    }
}
