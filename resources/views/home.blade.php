@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8 text-center">Welcome to Liberu Real Estate</h1>

    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-6 text-center">Featured Properties</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredProperties as $property)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden transition duration-300 hover:shadow-xl">
                    <img src="{{ $property->images->first()->url ?? asset('images/placeholder.jpg') }}" alt="{{ $property->title }}" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $property->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($property->description, 100) }}</p>
                        <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                            <span><i class="fas fa-bed mr-1"></i> {{ $property->bedrooms }} bed</span>
                            <span><i class="fas fa-bath mr-1"></i> {{ $property->bathrooms }} bath</span>
                            <span><i class="fas fa-ruler-combined mr-1"></i> {{ $property->area_sqft }} sqft</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-green-600">${{ number_format($property->price, 2) }}</span>
                            <a href="{{ route('property.detail', $property->property_id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-6 text-center">All Properties</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($properties as $property)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden transition duration-300 hover:shadow-xl">
                    <img src="{{ $property->images->first()->url ?? asset('images/placeholder.jpg') }}" alt="{{ $property->title }}" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $property->title }}</h3>
                        <p class="text-gray-600 mb-4">{{
