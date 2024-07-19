<div>
    <livewire:advanced-property-search />

    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($properties as $property)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if($property->images->isNotEmpty())
                    <img src="{{ $property->images->first()->image_url }}" alt="{{ $property->title }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-4">
                    <h3 class="text-xl font-semibold mb-2">{{ $property->title }}</h3>
                    <p class="text-gray-600 mb-2">{{ $property->location }}</p>
                    <p class="text-lg font-bold text-green-600 mb-2">${{ number_format($property->price, 2) }}</p>
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>{{ $property->bedrooms }} bed</span>
                        <span>{{ $property->bathrooms }} bath</span>
                        <span>{{ $property->area_sqft }} sqft</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Type: {{ ucfirst($property->property_type) }}</p>
                    @if($property->features->isNotEmpty())
                        <p class="text-sm text-gray-600 mb-2">
                            Amenities: {{ $property->features->pluck('feature_name')->implode(', ') }}
                        </p>
                    @endif
                    <div class="text-sm text-gray-600 mb-4">
                        {{ Str::limit($property->description, 150) }}
                    </div>
                    <a href="{{ route('property.show', $property->property_id) }}" class="block w-full text-center bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition duration-200">
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-600">
                No properties found matching your criteria.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $properties->links() }}
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
