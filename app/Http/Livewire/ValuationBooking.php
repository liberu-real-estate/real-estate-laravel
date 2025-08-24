<?php

namespace App\Http\Livewire;

use Exception;
use Livewire\Component;
use App\Models\AppointmentType;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;

class ValuationBooking extends Component
{
    public $selectedDate;
    public $selectedTime;
    public $userName;
    public $userContact;
    public $notes;
    public $availableDates = [];
    public $availableTimeSlots = [];
    public $appointmentType;

    public function mount()
    {
        $this->appointmentType = AppointmentType::where('name', 'Valuation')->firstOrFail();
        $this->availableDates = $this->getAvailableDates();
    }

    private function getAvailableDates()
    {
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(30);
        return collect($startDate->range($endDate))->map(function ($date) {
            return $date->format('Y-m-d');
        })->toArray();
    }

    public function updatedSelectedDate($value)
    {
        $this->availableTimeSlots = $this->getAvailableTimeSlots($value);
    }

    private function getAvailableTimeSlots($date)
    {
        $workingHours = [
            '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'
        ];

        $bookedSlots = Appointment::whereDate('appointment_date', $date)
            ->pluck('appointment_date')
            ->map(function ($dateTime) {
                return Carbon::parse($dateTime)->format('H:i');
            })
            ->toArray();

        return array_diff($workingHours, $bookedSlots);
    }

    public function bookValuation()
    {
        $this->validate([
            'selectedDate' => 'required|date',
            'selectedTime' => 'required',
            'userName' => 'required|string',
            'userContact' => 'required|string',
        ]);

        try {
            // Check if the time slot is still available
            if (!in_array($this->selectedTime, $this->getAvailableTimeSlots($this->selectedDate))) {
                throw new Exception('Selected time slot is no longer available.');
            }

            // Get the default staff member (you may want to implement a more sophisticated assignment logic)
            $defaultStaffId = User::role('staff')->first()->id ?? null;

            Appointment::create([
                'appointment_type_id' => $this->appointmentType->id,
                'appointment_date' => Carbon::parse($this->selectedDate . ' ' . $this->selectedTime),
                'user_id' => auth()->id() ?? null,
                'staff_id' => $defaultStaffId,
                'name' => $this->userName,
                'contact' => $this->userContact,
                'notes' => $this->notes,
                'status' => 'requested',
            ]);

            session()->flash('message', 'Valuation appointment requested successfully for ' . $this->selectedDate . ' at ' . $this->selectedTime);
            $this->reset(['selectedDate', 'selectedTime', 'userName', 'userContact', 'notes']);
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
