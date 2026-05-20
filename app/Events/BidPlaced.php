<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Bid;

class BidPlaced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bid;

    public function __construct(Bid $bid)
    {
        $this->bid = $bid;
    }

    public function broadcastOn()
    {
        return new Channel('auction.' . $this->bid->auction_id);
    }

    public function broadcastAs()
    {
        return 'BidPlaced';
    }

    public function broadcastWith()
    {
        return [
            'amount' => $this->bid->amount,
            'user' => $this->bid->user->name,
            'time' => $this->bid->created_at->toDateTimeString(),
        ];
    }
}