@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-2">
                    My Wishlist
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    {{ $totalFavorites }} {{ Str::plural('property', $totalFavorites) }} saved
                </p>
            </div>

            <!-- Search and Sort -->
            <div class="mb-6 flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <input type="text" 
                               wire:model.debounce.300ms="search" 
                               id="search"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                               placeholder="Search properties...">
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <button wire:click="sortByColumn('created_at')" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                        Date Added @if($sortBy === 'created_at') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </button>
                    <button wire:click="sortByColumn('price')" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                        Price @if($sortBy === 'price') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </button>
                </div>
            </div>

            @if(session()->has('success'))
                <div class="mb-4 p-4 text-sm text-green-800 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Properties Grid -->
            @if($favorites->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($favorites as $property)
                        <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 relative">
                            <!-- Remove from Wishlist Button -->
                            <button wire:click="removeFavorite({{ $property->id }})"
                                    class="absolute top-3 right-3 z-10 p-2 bg-white rounded-full shadow-lg hover:bg-red-50 transition-colors"
                                    title="Remove from wishlist">
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <a href="{{ route('property.detail', $property->id) }}">
                                @if($property->images->count() > 0)
                                    <img class="rounded-t-lg w-full h-48 object-cover" 
                                         src="{{ $property->images->first()->url }}" 
                                         alt="{{ $property->title }}">
                                @else
                                    <div class="rounded-t-lg w-full h-48 bg-gray-300 flex items-center justify-center">
                                        <span class="text-gray-500">No image</span>
                                    </div>
                                @endif
                            </a>

                            <div class="p-5">
                                <a href="{{ route('property.detail', $property->id) }}">
                                    <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                                        {{ $property->title }}
                                    </h5>
                                </a>
                                
                                <p class="mb-2 text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $property->location }}
                                </p>

                                <div class="mb-3 flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                    <span>{{ $property->bedrooms }} beds</span>
                                    <span>{{ $property->bathrooms }} baths</span>
                                    <span>{{ number_format($property->area_sqft) }} sqft</span>
                                </div>

                                <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                    ${{ number_format($property->price) }}
                                </p>

                                @if(isset($property->favorited_at))
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        Added {{ $property->favorited_at->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $favorites->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No properties in your wishlist</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by adding properties you're interested in.</p>
                    <div class="mt-6">
                        <a href="{{ route('property.list') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Browse Properties
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
