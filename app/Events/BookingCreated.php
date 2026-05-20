<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function broadcastOn()
    {
        return new Channel('bookings');
    }

    public function broadcastWith()
    {
        return [
            'property_id' => $this->booking->property_id,
            'date' => $this->booking->date,
            'time' => $this->booking->time,
            'available_dates' => $this->booking->property->getAvailableDatesForTeam(),
        ];
    }
}