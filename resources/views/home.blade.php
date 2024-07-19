@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Welcome to Liberu Real Estate</h1>

    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">Featured Properties</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredProperties as $property)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ $property->images->first()->url ?? asset('images/placeholder.jpg') }}" alt="{{ $property->title }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">{{ $property->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($property->description, 100) }}</p>
                        <a href="{{ url('/properties/'.$property->property_id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section>
        <h2 class="text-2xl font-semibold mb-4">All Properties</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($properties as $property)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ $property->images->first()->url ?? asset('images/placeholder.jpg') }}" alt="{{ $property->title }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">{{ $property->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($property->description, 100) }}</p>
                        <a href="{{ url('/properties/'.$property->property_id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mt-12">
        <h2 class="text-2xl font-semibold mb-4">About Our Business</h2>
        <p class="text-gray-600">Liberu Real Estate is revolutionizing the real estate industry with innovative tools and seamless workflows. Our platform empowers real estate professionals, property owners, and investors.</p>
    </section>
</div>
@endsection
