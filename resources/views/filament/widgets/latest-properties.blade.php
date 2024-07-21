<x-filament::widget>
    <x-filament::card>
        <h2 class="text-lg font-medium">Latest Properties</h2>
        <ul class="mt-4 space-y-2">
            @foreach($this->getLatestProperties() as $property)
                <li>{{ $property->name }} - {{ $property->created_at->diffForHumans() }}</li>
            @endforeach
        </ul>
    </x-filament::card>
</x-filament::widget>