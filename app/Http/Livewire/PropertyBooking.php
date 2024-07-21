<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;

class PropertyBooking extends Component
{
    public $propertyId;
    public $selectedDate;
    public $userName;
    public $userContact;
    public $notes;
    public $availableDates = [];

    protected $rules = [
        'selectedDate' => 'required|date|after_or_equal:today',
        'userName' => 'required|string|max:255',
        'userContact' => 'required|string|max:255',
        'notes' => 'nullable|string|max:1000',
    ];

    public function mount($propertyId)
    {
        $this->propertyId = $propertyId;
        $this->availableDates = Property::find($this->propertyId)->getAvailableDates();
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->validate(['selectedDate' => $this->rules['selectedDate']]);
    }

    public function bookViewing()
    {
        $this->validate();

        try {
            // Check if the date is still available
            $availableDates = Property::find($this->propertyId)->getAvailableDates();
            if (!in_array($this->selectedDate, $availableDates)) {
                throw new \Exception('Selected date is no longer available.');
            }

            // Get the default staff member using Spatie\Permission
            $defaultStaffId = User::role('staff')->first()->id ?? null;

            Booking::create([
                'property_id' => $this->propertyId,
                'date' => Carbon::parse($this->selectedDate)->format('Y-m-d'),
                'user_id' => auth()->id(),
                'name' => $this->userName,
                'contact' => $this->userContact,
                'notes' => $this->notes,
                'staff_id' => $defaultStaffId,
            ]);

            session()->flash('message', 'Viewing scheduled successfully for ' . $this->selectedDate);
            $this->reset(['selectedDate', 'userName', 'userContact', 'notes']);
        } catch (\Exception $e) {
            \Log::error('Booking failed: ' . $e->getMessage());

            $errorMessage = 'Failed to schedule viewing. ';
            if ($e instanceof \Illuminate\Database\QueryException) {
                $errorMessage .= 'A database error occurred. ';
            } elseif ($e instanceof \Illuminate\Validation\ValidationException) {
                $errorMessage .= 'Please check your input and try again. ';
            } elseif ($e->getMessage() === 'Selected date is no longer available.') {
                $errorMessage .= 'The selected date is no longer available. Please choose another date. ';
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
        ]);
    }
}

