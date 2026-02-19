<?php

namespace App\Http\Livewire;

use Exception;
use Log;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use App\Models\Property;
use App\Models\Booking;
use App\Models\User;
use App\Events\BookingCreated;
use App\Notifications\BookingNotification;
use App\Services\CalendarIntegrationService;
use Carbon\Carbon;

class PropertyBooking extends Component
{
    public $propertyId;
    public $selectedDate;
    public $selectedTime;
    public $userName;
    public $userEmail;
    public $userContact;
    public $notes;
    public $availableDates = [];
    public $availableTimeSlots = [];
    public $bookingConfirmed = false;
    public $confirmedBookingId = null;
    public $googleCalendarUrl = null;
    public $outlookCalendarUrl = null;

    protected $rules = [
        'selectedDate' => 'required|date|after_or_equal:today',
        'selectedTime' => 'required|string',
        'userName' => 'required|string|max:255',
        'userEmail' => 'nullable|email|max:255',
        'userContact' => 'required|string|max:255',
        'notes' => 'nullable|string|max:1000',
    ];

    public function mount($propertyId)
    {
        $this->propertyId = $propertyId;
        $property = Property::with('team')->find($this->propertyId);
        $this->availableDates = $property->getAvailableDatesForTeam();
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

        $bookedSlots = Booking::where('property_id', $this->propertyId)
            ->whereDate('date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->pluck('time')
            ->map(fn($t) => Carbon::parse($t)->format('H:i'))
            ->toArray();

        return array_values(array_diff($workingHours, $bookedSlots));
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->validate(['selectedDate' => $this->rules['selectedDate']]);
        $this->updatedSelectedDate($date);
    }

    public function bookViewing()
    {
        $this->validate();

        try {
            $availableDates = Property::find($this->propertyId)->getAvailableDates();
            if (!in_array($this->selectedDate, $availableDates)) {
                throw new Exception('Selected date is no longer available.');
            }

            if (!in_array($this->selectedTime, $this->getAvailableTimeSlots($this->selectedDate))) {
                throw new Exception('Selected time slot is no longer available.');
            }

            $defaultStaffId = User::role('staff')->first()->id ?? null;

            $booking = Booking::create([
                'property_id' => $this->propertyId,
                'date' => Carbon::parse($this->selectedDate)->format('Y-m-d'),
                'time' => $this->selectedTime,
                'user_id' => auth()->id(),
                'name' => $this->userName,
                'contact' => $this->userContact,
                'notes' => $this->notes,
                'staff_id' => $defaultStaffId,
                'status' => 'confirmed',
                'booking_type' => 'viewing',
            ]);

            $calendarService = app(CalendarIntegrationService::class);
            $this->googleCalendarUrl = $calendarService->getBookingGoogleCalendarUrl($booking);
            $this->outlookCalendarUrl = $calendarService->getBookingOutlookCalendarUrl($booking);
            $this->confirmedBookingId = $booking->id;
            $this->bookingConfirmed = true;

            broadcast(new BookingCreated($booking))->toOthers();

            if (auth()->check()) {
                auth()->user()->notify(new BookingNotification($booking, 'confirmed'));
            }

            session()->flash('message', 'Viewing scheduled successfully for ' . Carbon::parse($this->selectedDate)->format('F j, Y') . ' at ' . $this->selectedTime);
            $this->reset(['selectedDate', 'selectedTime', 'userName', 'userEmail', 'userContact', 'notes', 'availableTimeSlots']);
        } catch (Exception $e) {
            Log::error('Booking failed: ' . $e->getMessage());

            $errorMessage = 'Failed to schedule viewing. ';
            if ($e instanceof QueryException) {
                $errorMessage .= 'A database error occurred. ';
            } elseif ($e instanceof ValidationException) {
                $errorMessage .= 'Please check your input and try again. ';
            } elseif ($e->getMessage() === 'Selected date is no longer available.') {
                $errorMessage .= 'The selected date is no longer available. Please choose another date. ';
            } elseif ($e->getMessage() === 'Selected time slot is no longer available.') {
                $errorMessage .= 'The selected time slot is no longer available. Please choose another time. ';
            } else {
                $errorMessage .= 'An unexpected error occurred. ';
            }
            $errorMessage .= 'Please try again or contact support if the problem persists.';

            session()->flash('error', $errorMessage);
        }
    }

    public function render()
    {
        return view('livewire.property-booking', [
            'availableDates' => $this->availableDates,
            'availableTimeSlots' => $this->availableTimeSlots,
        ]);
    }
}
