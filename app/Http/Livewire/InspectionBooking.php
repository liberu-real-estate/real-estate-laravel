<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\Inspection;
use App\Models\User;
use Carbon\Carbon;

class InspectionBooking extends Component
{
    public $propertyId;
    public $selectedDate;
    public $selectedTime;
    public $notes;
    public $availableDates = [];
    public $availableTimeSlots = [];

    protected $rules = [
        'selectedDate' => 'required|date|after_or_equal:today',
        'selectedTime' => 'required',
        'notes' => 'nullable|string|max:1000',
    ];

    public function mount($propertyId)
    {
        $this->propertyId = $propertyId;
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

        $bookedSlots = Inspection::whereDate('scheduled_at', $date)
            ->pluck('scheduled_at')
            ->map(function ($dateTime) {
                return Carbon::parse($dateTime)->format('H:i');
            })
            ->toArray();

        return array_diff($workingHours, $bookedSlots);
    }

    public function bookInspection()
    {
        $this->validate();

        try {
            $property = Property::findOrFail($this->propertyId);
            $inspector = User::role('inspector')->inRandomOrder()->first();

            Inspection::create([
                'property_id' => $this->propertyId,
                'inspector_id' => $inspector->id,
                'tenant_id' => auth()->id(),
                'scheduled_at' => Carbon::parse($this->selectedDate . ' ' . $this->selectedTime),
                'status' => 'scheduled',
                'notes' => $this->notes,
            ]);

            session()->flash('message', 'Inspection scheduled successfully for ' . $this->selectedDate . ' at ' . $this->selectedTime);
            $this->reset(['selectedDate', 'selectedTime', 'notes']);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to schedule inspection: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.inspection-booking', [
            'property' => Property::find($this->propertyId),
        ]);
    }
}