<div>
    <livewire:advanced-property-search />
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
