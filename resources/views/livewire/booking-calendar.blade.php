<div>
    <div class="calendar">
        @foreach($dates as $date)
            <div class="calendar-date" wire:click="selectDate('{{ $date }}')">
                {{ $date }}
            </div>
        @endforeach
    </div>

    <div class="bookings">
        <h3>Bookings</h3>
        @foreach($bookings as $booking)
            <div class="booking">
                <p>Property: {{ $booking->property->name }}</p>
                <p>Date: {{ $booking->date->toFormattedDateString() }}</p>
                <button wire:click="bookProperty('{{ $booking->property_id }}', '{{ $booking->date->toDateString() }}')">Book Again</button>
            </div>
        @endforeach
    </div>

    <div class="booking-form">
        <select wire:model="selectedProperty">
            @foreach($properties as $property)
                <option value="{{ $property->id }}">{{ $property->name }}</option>
            @endforeach
        </select>
        <input type="date" wire:model="selectedDate">
        <button wire:click="bookProperty(selectedProperty, selectedDate)">Book Property</button>
    </div>

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @error('selectedDate') <span class="error">{{ $message }}</span> @enderror
    @error('selectedProperty') <span class="error">{{ $message }}</span> @enderror
</div>
