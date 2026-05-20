<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Auction;
use App\Models\Bid;

class AuctionNotification extends Notification
{
    use Queueable;

    protected $auction;
    protected $bid;
    protected $type;

    public function __construct(Auction $auction, Bid $bid = null, $type = 'bid_update')
    {
        $this->auction = $auction;
        $this->bid = $bid;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)->subject('Auction Update');

        if ($this->type === 'bid_update') {
            $message->line('A new bid has been placed on the auction you\'re participating in.')
                    ->line('Current highest bid: $' . number_format($this->auction->current_bid, 2))
                    ->action('View Auction', url('/auctions/' . $this->auction->id));
        } elseif ($this->type === 'auction_ended') {
            $message->line('The auction you participated in has ended.')
                    ->line('Final price: $' . number_format($this->auction->current_bid, 2))
                    ->action('View Results', url('/auctions/' . $this->auction->id . '/results'));
        }

        return $message;
    }

    public function toArray($notifiable)
    {
        return [
            'auction_id' => $this->auction->id,
            'type' => $this->type,
            'current_bid' => $this->auction->current_bid,
        ];
    }
}