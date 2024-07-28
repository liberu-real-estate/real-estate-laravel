<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Booking;
use Carbon\Carbon;

class ManageBooking extends Component
{
    public $booking;
    public $newDate;
    public $newTime;

    protected $rules = [
        'newDate' => 'required|date|after:today',
        'newTime' => 'required|date_format:H:i',
    ];

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->newDate = $booking->date->format('Y-m-d');
        $this->newTime = $booking->time->format('H:i');
    }

    public function cancelBooking()
    {
        $this->booking->cancel();
        session()->flash('message', 'Booking cancelled successfully.');
    }

    public function rescheduleBooking()
    {
        $this->validate();

        $this->booking->reschedule($this->newDate, $this->newTime);
        session()->flash('message', 'Booking rescheduled successfully.');
    }

    public function render()
    {
        return view('livewire.manage-booking');
    }
}