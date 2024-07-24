<div>
@section('content')
<article class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
<img src="{{ $property->getFirstMediaUrl('images') ?: asset('build/images/property-placeholder.png') }}" alt="{{ $property->title }}" class="w-full h-auto rounded-lg shadow-lg">
        </div>
        <div>
            <h1 class="text-3xl font-bold mb-4">{{ $property->title }}</h1>
            <p class="text-2xl text-gray-700 mb-4">${{ number_format($property->price, 2) }}</p>

            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Category</h2>
                <div class="flex flex-wrap gap-2">
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $property->category->name ?? 'None'}}</span>
                        <p class="text-gray-500">No category</p>
                </div>
            </div>

            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Features & Amenities</h2>
                <ul class="list-disc list-inside grid grid-cols-2 gap-2">
                    @forelse($property->features ?? [] as $feature)
                        <li>{{ $feature->name }}</li>
                    @empty
                        <li class="text-gray-500">No features available</li>
                    @endforelse
                </ul>
            </div>

            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Description</h2>
                <p class="text-gray-600">{{ $property->description }}</p>
            </div>

            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Neighborhood</h2>
                <p class="text-gray-600">{{ $property->neighborhood_details ?? 'No neighborhood details available' }}</p>
            </div>
            
            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Branch/Team</h2>
                <p class="text-gray-600">{{ $team->name ?? 'No team information available' }}</p>
            </div>
            <p class="text-2xl text-gray-700 mb-4">${{ number_format($property->price, 2) }}</p>

            @livewire('property-booking', ['propertyId' => $property->id])
            
            <div class="mt-8">
                <h2 class="text-2xl font-bold mb-4">Book a Valuation</h2>
                @livewire('valuation-booking')
            </div>
            
            @if($isLettingsProperty)
                <div class="mt-8">
                    <a href="{{ route('tenancy.apply', $property->id) }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Apply for Tenancy
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
</article>
@endsection
</div>
