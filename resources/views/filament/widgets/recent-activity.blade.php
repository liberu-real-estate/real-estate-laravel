<x-filament::widget>
    <x-filament::card>
        <h2 class="text-lg font-medium">Recent Activity</h2>
        <div class="mt-4 space-y-4">
            <div>
                <h3 class="text-md font-medium">New Properties</h3>
                <ul class="mt-2 space-y-1">
                    @foreach($this->getRecentActivity()['properties'] as $property)
                        <li>{{ $property->name }} - {{ $property->created_at->diffForHumans() }}</li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h3 class="text-md font-medium">New Bookings</h3>
                <ul class="mt-2 space-y-1">
                    @foreach($this->getRecentActivity()['bookings'] as $booking)
                        <li>{{ $booking->property->name }} - {{ $booking->created_at->diffForHumans() }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>