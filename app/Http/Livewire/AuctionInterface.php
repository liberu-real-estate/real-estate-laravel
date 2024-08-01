<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AuctionNotification;

class AuctionInterface extends Component
{
    public $auction;
    public $bidAmount;
    public $currentBid;
    public $timeRemaining;

    protected $listeners = ['refreshAuction' => '$refresh'];

    public function mount(Auction $auction)
    {
        $this->auction = $auction;
        $this->currentBid = $auction->current_bid;
        $this->updateTimeRemaining();
    }

    public function render()
    {
        return view('livewire.auction-interface');
    }

    public function placeBid()
    {
        $this->validate([
            'bidAmount' => ['required', 'numeric', 'min:' . ($this->auction->current_bid + $this->auction->minimum_increment)],
        ]);

        $bid = new Bid([
            'user_id' => Auth::id(),
            'auction_id' => $this->auction->id,
            'amount' => $this->bidAmount,
        ]);

        $bid->save();

        $this->auction->current_bid = $this->bidAmount;
        $this->auction->save();

        $this->currentBid = $this->bidAmount;
        $this->bidAmount = '';

        $this->emit('bidPlaced');

        // Notify other participants
        foreach ($this->auction->bids->pluck('user')->unique() as $user) {
            if ($user->id !== Auth::id()) {
                $user->notify(new AuctionNotification($this->auction, $bid, 'bid_update'));
            }
        }
    }

    public function updateTimeRemaining()
    {
        $now = now();
        $end = $this->auction->end_time;
        $this->timeRemaining = $end->diffForHumans($now, ['parts' => 2]);
    }

    public function getListeners()
    {
        return [
            'echo:auction.' . $this->auction->id . ',BidPlaced' => 'handleBidPlaced',
            'refreshAuction' => '$refresh',
        ];
    }

    public function handleBidPlaced()
    {
        $this->auction->refresh();
        $this->currentBid = $this->auction->current_bid;
    }
}