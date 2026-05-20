<x-filament::widget>
    <x-filament::card>
        <h2 class="text-lg font-medium">Recent Bookings</h2>
        <ul class="mt-4 space-y-2">
            @foreach($this->getRecentBookings() as $booking)
                <li>{{ $booking->property->name }} - {{ $booking->created_at->diffForHumans() }}</li>
            @endforeach
        </ul>
    </x-filament::card>
</x-filament::widget>