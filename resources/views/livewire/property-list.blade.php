<div>
    <livewire:advanced-property-search />

    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($properties as $property)
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold">{{ $property->title }}</h3>
                <p class="text-gray-600">{{ $property->location }}</p>
                <p class="mt-2">Price: ${{ number_format($property->price, 2) }}</p>
                <p>Bedrooms: {{ $property->bedrooms }}</p>
                <p>Bathrooms: {{ $property->bathrooms }}</p>
                <p>Area: {{ $property->area_sqft }} sqft</p>
                <p>Type: {{ ucfirst($property->property_type) }}</p>
                @if($property->features->isNotEmpty())
                    <p class="mt-2">Amenities: {{ $property->features->pluck('feature_name')->implode(', ') }}</p>
                @endif
                <div class="text-sm text-gray-600 mt-2">
                    {{ Str::limit($property->description, 100) }}
                </div>
            </div>
        @endforeach
    </div>
</div>
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
