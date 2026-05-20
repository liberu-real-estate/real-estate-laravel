<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Support\Facades\Auth;

class AuctionBidding extends Component
{
    public $auction;
    public $bidAmount;

    protected $listeners = ['refreshAuction' => '$refresh'];

    public function mount(Auction $auction)
    {
        $this->auction = $auction;
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

        $this->auction->refresh();
        $this->emit('bidPlaced');
    }

    public function render()
    {
        return view('livewire.auction-bidding');
    }
}