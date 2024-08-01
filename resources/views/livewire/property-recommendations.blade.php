<div>
    <h2 class="text-xl font-semibold mb-4">Recommended Properties</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($recommendations as $property)
            <div class="bg-white shadow rounded-lg p-4">
                <img src="{{ $property->images->first()->url ?? 'default-image.jpg' }}" alt="{{ $property->title }}" class="w-full h-48 object-cover mb-2">
                <h3 class="text-lg font-semibold">{{ $property->title }}</h3>
                <p class="text-gray-600">{{ $property->location }}</p>
                <p class="text-blue-600 font-bold">${{ number_format($property->price, 2) }}</p>
                <a href="{{ route('properties.show', $property) }}" class="mt-2 inline-block bg-blue-500 text-white px-4 py-2 rounded">View Details</a>
            </div>
        @endforeach
    </div>
</div>