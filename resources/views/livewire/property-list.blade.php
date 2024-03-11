<div>
    <form class="space-y-4" wire:submit.prevent="search">
        <x-filament::form>
            <x-filament::input
                type="text"
                placeholder="Search properties..."
                wire:model="search"
            />
        </x-filament::form>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($properties as $property)
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold">{{ $property->title }}</h3>
                <p>{{ $property->location }}</p>
                <div class="text-sm text-gray-600 mt-2">
                    {{ $property->description }}
                </div>
            </div>
        @endforeach
    </div>
</div>
