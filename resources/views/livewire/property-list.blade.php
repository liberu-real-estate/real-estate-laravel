
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Property Listings</h1>

    <div class="mb-8">
        <form wire:submit.prevent="search" class="flex flex-wrap -mx-2">
            <div class="w-full md:w-1/2 px-2 mb-4 md:mb-0">
                <input type="text" wire:model.debounce.300ms="search" placeholder="Search properties" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="w-full md:w-1/4 px-2">
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">Search</button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($properties as $property)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden transition duration-300 hover:shadow-xl">
                @if($property->template)
                    {!! $this->renderTemplate($property) !!}
                @else
                    <img src="{{ $property->getFirstMediaUrl('images') ?: asset('build/images/property-placeholder.png') }}" alt="{{ $property->title }}" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $property->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($property->description, 100) }}</p>
                        <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                            <span><i class="fas fa-bed mr-1"></i> {{ $property->bedrooms }} bed</span>
                            <span><i class="fas fa-bath mr-1"></i> {{ $property->bathrooms }} bath</span>
                            <span><i class="fas fa-ruler-combined mr-1"></i> {{ $property->area_sqft }} sqft</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-green-600">&pound{{ number_format($property->price, 2) }}</span>
                            <a href="{{ route('property.detail', $property->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">View Details</a>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-3">
                <p class="text-center text-gray-600">No properties found matching your criteria.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $properties->links() }}
    </div>

    @if($properties->count() > 0)
        <div class="mt-8 text-sm text-gray-600">
            <p>Total properties: {{ $properties->total() }}</p>
            <p>Current page: {{ $properties->currentPage() }}</p>
            <p>Last page: {{ $properties->lastPage() }}</p>
            <p>Properties on this page: {{ $properties->count() }}</p>
        </div>
    @endif
</div>
@endsection
