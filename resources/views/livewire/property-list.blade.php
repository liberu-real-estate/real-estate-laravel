<div>
    <livewire:advanced-property-search />

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($properties as $property)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="relative pb-2/3">
                    @if($property->images->isNotEmpty())
                        <img src="{{ $property->images->first()->image_url }}" alt="{{ $property->title }}" class="absolute h-full w-full object-cover">
                    @else
                        <div class="absolute h-full w-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">No image available</span>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-xl font-semibold mb-2">{{ $property->title ?? 'Untitled Property' }}</h3>
                    <p class="text-gray-600 mb-2">{{ $property->location ?? 'Location not specified' }}</p>
                    <p class="text-lg font-bold text-green-600 mb-2">${{ number_format($property->price ?? 0, 2) }}</p>
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>{{ $property->bedrooms ?? 'N/A' }} bed</span>
                        <span>{{ $property->bathrooms ?? 'N/A' }} bath</span>
                        <span>{{ $property->area_sqft ?? 'N/A' }} sqft</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Type: {{ ucfirst($property->property_type ?? 'Not specified') }}</p>
                    @if($property->features->isNotEmpty())
                        <p class="text-sm text-gray-600 mb-2">
                            Amenities: {{ $property->features->pluck('feature_name')->implode(', ') }}
                        </p>
                    @endif
                    <div class="text-sm text-gray-600 mb-4">
                        {{ Str::limit($property->description ?? 'No description available', 150) }}
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
