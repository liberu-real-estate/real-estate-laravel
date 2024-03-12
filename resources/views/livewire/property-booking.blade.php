<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="bookViewing">
        <div class="form-group">
            <label for="bookingDate">Select a Date</label>
            <input type="date" id="bookingDate" class="form-control" wire:model="selectedDate" min="{{ now()->toDateString() }}">
            @error('selectedDate') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="userName">Name</label>
            <input type="text" id="userName" class="form-control" wire:model="userName">
            @error('userName') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="userContact">Contact Information</label>
            <input type="text" id="userContact" class="form-control" wire:model="userContact">
            @error('userContact') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="notes">Additional Notes</label>
            <textarea id="notes" class="form-control" wire:model="notes"></textarea>
            @error('notes') <span class="error">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Book Viewing</button>
    </form>
</div>
