@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <p class="text-3xl font-serif  mb-8">Properties for Sale & Rent</p>

        <div class="mb-8">
            <form wire:submit.prevent="search" class="flex flex-wrap -mx-2">
                <div class="w-full md:w-1/2 px-2 mb-4 md:mb-0">
                    <input type="text" wire:model.debounce.300ms="search" placeholder="Search properties"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-100">
                </div>
                <div class="w-1/2 md:w-1/4 lg:w-1/6 px-2">
                    <button type="submit"
                        class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200 cursor-pointer">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-5 gap-4">
            @forelse($properties as $property)
            <div class="flex flex-col cursor-pointer space-y-20 min-w-screen animated fadeIn faster outline-none ">
                <div class="block rounded-lg bg-white w-72 p-2 border border-slate-100 hover:border-slate-400 rounded-md" href="{{ route('property.detail', $property->id) }}">
                    <div class="relative overflow-hidden bg-cover bg-no-repeat" data-te-ripple-init data-te-ripple-color="light">
                        <img src="{{ $property->getFirstMediaUrl('images') ?: asset('build/images/property-placeholder.png') }}"
                            alt="{{ $property->title }}" class="rounded-lg md:h-64 w-full">
                        <a href="#!">
                            <div
                                class="absolute bottom-0 left-0 right-0 top-0 h-full w-full overflow-hidden bg-[hsla(0,0%,98%,0.15)] bg-fixed opacity-0 transition duration-300 ease-in-out hover:opacity-100">
                            </div>
                        </a>
                    </div>
            
                    <div class="p-0">
                        <div class="flex justify-between">
                            <h5 class="mb-2 mt-2 text-sm font-bold leading-tight text-neutral-800 dark:text-neutral-50">
                                {{ Str::limit($property->title, 35) }}
                            </h5>
                        </div>
                        <p class="mb-1 text-sm text-neutral-600 dark:text-neutral-200">
                            {{ Str::limit($property->description, 100) }}
                        </p>
            
                        <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                            <div class="flex items-center">
                                <h5 class="w-24 text-sm font-bold text-neutral-800 dark:text-neutral-50">BED</h5>
                                <p class="text-base text-neutral-600 dark:text-neutral-200">{{ $property->bedrooms }}</p>
                            </div>
                            <div class="flex items-center">
                                <h5 class="w-24 text-sm font-bold text-neutral-800 dark:text-neutral-50">SQFT</h5>
                                <p class="text-base text-neutral-600 dark:text-neutral-200">{{ $property->area_sqft }}</p>
                            </div>
                            <div class="flex items-center">
                                <h5 class="w-24 text-sm font-bold text-neutral-800 dark:text-neutral-50">BATH ROOM</h5>
                                <p class="text-base text-neutral-600 dark:text-neutral-200">{{ $property->bathrooms }}</p>
                            </div>
                          
                        </div>
            
                        <div class="grid grid-cols-2 gap-2 mt-3">
                            <div class="font-semibold text-neutral-800 dark:text-neutral-50">PRICE</div>
                            <div>
                                <span
                                    class="text-xl font-bold text-green-600">&pound{{ number_format($property->price, 2) }}</span>
                            </div>
                        </div>
            
                        <div class="grid grid-cols-1 gap-2 mt-3">
                        
                            <a href="{{ route('property.detail', $property->id) }}"
                                class="w-full text-indigo px-4 py-2 rounded-lg shadow-md hover:border-green-500 hover:shadow-lg border border-green-300 transition duration-200 transform focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-center">
                                VIEW DETAILS
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            <div class="flex items-center justify-between border-gray-100 bg-white px-4 py-3 sm:px-6">
                <div class="flex flex-1 justify-between sm:hidden">
                    <a href="#"
                        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                    <a href="#"
                        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ $properties->firstItem() }}</span>
                            to
                            <span class="font-medium">{{ $properties->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $properties->total() }}</span>
                            results
                        </p>
                    </div>
                    <div>
                        <div>
                            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                @if ($properties->onFirstPage())
                                    <span
                                        class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300">
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @else
                                    <a href="{{ $properties->previousPageUrl() }}"
                                        class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @endif

                                @foreach ($properties->links()->elements[0] as $pageNumber => $url)
                                    @if ($pageNumber == $properties->currentPage())
                                        <span
                                            class="relative z-10 inline-flex items-center  px-4 py-2 text-sm font-semibold text-green border border-green-500 focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $pageNumber }}</span>
                                    @else
                                        <a href="{{ $url }}"
                                            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">{{ $pageNumber }}</a>
                                    @endif
                                @endforeach

                                @if ($properties->hasMorePages())
                                    <a href="{{ $properties->nextPageUrl() }}"
                                        class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                        <span class="sr-only">Next</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @else
                                    <span
                                        class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300">
                                        <span class="sr-only">Next</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
