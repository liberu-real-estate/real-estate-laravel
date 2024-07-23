@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-2xl font-bold mb-4">Recommended Properties</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($recommendations as $property)
            <div class="border rounded-lg shadow-sm p-4">
                <h3 class="text-lg font-semibold">{{ $property->title }}</h3>
                <p class="mt-2">Price: ${{ number_format($property->price) }}</p>
                <p>Bedrooms: {{ $property->bedrooms }}</p>
                <p>Bathrooms: {{ $property->bathrooms }}</p>
                <p>Type: {{ ucfirst($property->property_type) }}</p>
                <p class="mt-2">Features: {{ $property->features->pluck('feature_name')->implode(', ') }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection