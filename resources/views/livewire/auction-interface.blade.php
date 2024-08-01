<div>
    <h2>{{ $auction->property->title }} Auction</h2>
    <p>Current Bid: ${{ number_format($currentBid, 2) }}</p>
    <p>Time Remaining: {{ $timeRemaining }}</p>

    <form wire:submit.prevent="placeBid">
        <div>
            <label for="bidAmount">Your Bid:</label>
            <input type="number" id="bidAmount" wire:model="bidAmount" step="0.01" min="{{ $currentBid + $auction->minimum_increment }}">
        </div>
        <button type="submit">Place Bid</button>
    </form>

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @error('bidAmount')
        <span class="error">{{ $message }}</span>
    @enderror

    <h3>Bid History</h3>
    <ul>
        @foreach ($auction->bids()->orderBy('created_at', 'desc')->take(5)->get() as $bid)
            <li>${{ number_format($bid->amount, 2) }} by {{ $bid->user->name }} at {{ $bid->created_at->format('M d, Y H:i:s') }}</li>
        @endforeach
    </ul>
</div>