<div>
    @section('content')
        {{-- <article class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <img src="{{ $property->getFirstMediaUrl('images') ?? asset('build/images/property-placeholder.png') }}" alt="{{ $property->title }}" onerror="this.onerror=null; this.src='{{asset('build/images/property-placeholder.png')}}';" class="w-full h-auto rounded-lg shadow-lg">
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
                @if ($neighborhood)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-lg font-semibold">Overview</h3>
                            <p class="text-gray-600">{{ $neighborhood->description }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Key Statistics</h3>
                            <ul class="list-disc list-inside">
                                <li>Population: {{ number_format($neighborhood->population) }}</li>
                                <li>Median Income: ${{ number_format($neighborhood->median_income) }}</li>
                                <li>Walk Score: {{ $neighborhood->walk_score }}/100</li>
                                <li>Transit Score: {{ $neighborhood->transit_score }}/100</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Schools</h3>
                            <ul class="list-disc list-inside">
                                @foreach ($neighborhood->schools as $school)
                                    <li>{{ $school['name'] }} - {{ $school['rating'] }}/10</li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Amenities</h3>
                            <ul class="list-disc list-inside">
                                @foreach ($neighborhood->amenities as $amenity)
                                    <li>{{ $amenity }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Last updated: {{ $neighborhood->last_updated->format('M d, Y H:i') }}</p>
                @else
                    <p class="text-gray-600">No neighborhood details available</p>
                @endif
            </div>
            
            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Energy Efficiency</h2>
                @if ($property->energy_rating && $property->energy_score)
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-xl mr-4" style="background-color: {{ $this->getEnergyRatingColor($property->energy_rating) }}">
                            {{ $property->energy_rating }}
                        </div>
                        <div>
                            <p class="font-semibold">Energy Efficiency Score: {{ $property->energy_score }}/100</p>
                            <p class="text-sm text-gray-600">Last updated: {{ $property->energy_rating_date->format('d M Y') }}</p>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">Properties are rated from A (most efficient) to G (least efficient). Learn more about <a href="#" class="text-blue-600 hover:underline" @click="$dispatch('open-modal', 'energy-efficiency-info')">energy efficiency ratings</a>.</p>
                @else
                    <p class="text-gray-600">Energy efficiency information not available for this property.</p>
                @endif
            </div>
            
            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Branch/Team</h2>
                <p class="text-gray-600">{{ $team->name ?? 'No team information available' }}</p>
            </div>
            <p class="text-2xl text-gray-700 mb-4">${{ number_format($property->price, 2) }}</p>

            @if (App\Providers\AppServiceProvider::isComponentEnabled('property-booking'))
                @livewire('property-booking', ['propertyId' => $property->id])
            @endif

            <div class="mt-8">
                <h2 class="text-2xl font-bold mb-4">Book a Valuation</h2>
                @if (App\Providers\AppServiceProvider::isComponentEnabled('valuation-booking'))
                    @livewire('valuation-booking')
                @endif
            </div>
            
            @if ($isLettingsProperty)
                <div class="mt-8">
                    <a href="{{ route('tenancy.apply', $property->id) }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Apply for Tenancy
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">Reviews</h2>
        @forelse($reviews as $review)
            <div class="bg-white shadow-lg rounded-lg p-6 mb-4">
                <div class="flex items-center mb-4">
                    <div class="font-bold mr-2">{{ $review->user->name }}</div>
                    <div class="text-yellow-500">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $review->rating)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                </div>
                <p class="text-gray-700">{{ $review->comment }}</p>
                <div class="text-sm text-gray-500 mt-2">{{ $review->created_at->format('M d, Y') }}</div>
            </div>
        @empty
            <p class="text-gray-500">No reviews yet.</p>
        @endforelse
    </div>

    @auth
        @livewire('property-review-form', ['propertyId' => $property->id])
    @else
        <p class="mt-4 text-gray-600">Please <a href="{{ route('login') }}" class="text-blue-500 hover:underline">login</a> to leave a review.</p>
    @endauth
</div>
</article> --}}
        <section>
            <section class="py-8 bg-white md:py-16 dark:bg-gray-900 antialiased">
                <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
                    <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
                        <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
                            <img src="{{ $property->getFirstMediaUrl('images') ?? asset('build/images/property-placeholder.png') }}"
                                alt="{{ $property->title }}"
                                onerror="this.onerror=null; this.src='{{ asset('build/images/property-placeholder.png') }}';"
                                class="w-full dark:hidden">
                        </div>

                        <div class="mt-6 sm:mt-8 lg:mt-0">
                            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
                                {{ $property->title }}
                            </h1>
                            <div class="mt-4 sm:items-center sm:gap-4 sm:flex">
                                <p class="text-2xl font-extrabold text-gray-900 sm:text-3xl dark:text-white">
                                    {{ App\Helpers\SiteSettingsHelper::get('currency') . ' ' . number_format($property->price, 2) }}
                                </p>

                                <div class="flex items-center gap-2 mt-2 sm:mt-0">
                                    <div class="flex items-center gap-1">
                                        @forelse($reviews as $review)
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <svg class="w-4 h-4 text-yellow-300" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-yellow-300" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="" viewBox="0 0 24 24">
                                                        <path
                                                            d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                                    </svg>
                                                @endif
                                            @endfor
                                            <a href="#"
                                                class="text-sm font-medium leading-none text-gray-900 underline hover:no-underline dark:text-white">
                                                {{ count($reviews) }} Reviews
                                            </a>
                                        @empty
                                            <p class="text-gray-500">No reviews yet.</p>
                                        @endforelse


                                    </div>

                                </div>
                            </div>

                            <div class="mt-6 sm:gap-4 sm:items-center sm:flex sm:mt-8">
                                <a href="#" title=""
                                    class="flex items-center justify-center py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    role="button">
                                    <svg class="w-5 h-5 -ms-2 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z" />
                                    </svg>
                                    Schedule a viewing
                                </a>

                                <a href="#" title=""
                                    class="text-white mt-4 sm:mt-0 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 flex items-center justify-center"
                                    role="button">
                                    <svg class="w-5 h-5 -ms-2 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                                    </svg>

                                    Book
                                </a>
                            </div>
                            <hr class="my-2 md:my-2 border-gray-200 dark:border-gray-800" />
                            <div class="">
                                <div class="">
                                    <span class="text-gray-500">Category</span>
                                    <span
                                        class="bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $property->category->name ?? 'None' }}</span>
                                </div>
                                <hr class="my-2 md:my-2 border-gray-200 dark:border-gray-800" />
                                <div class="my-2">
                                    <span class="text-gray-500">Features & Amenities</span>
                                    <ul class="list-disc list-inside grid grid-cols-2 gap-2">
                                        @forelse($property->features ?? [] as $feature)
                                            <li>{{ $feature->name }}</li>
                                        @empty
                                            <li class="text-gray-500">No features available</li>
                                        @endforelse
                                    </ul>
                                </div>
                                <hr class="my-2 md:my-2 border-gray-200 dark:border-gray-800" />
                                <div class="my-2">
                                    <span class="text-gray-500">Neighborhood</span>
                                    @if ($neighborhood)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <h3 class="text-lg font-semibold">Overview</h3>
                                                <p class="text-gray-600">{{ $neighborhood->description }}</p>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold">Key Statistics</h3>
                                                <ul class="list-disc list-inside">
                                                    <li>Population: {{ number_format($neighborhood->population) }}</li>
                                                    <li>Median Income: ${{ number_format($neighborhood->median_income) }}
                                                    </li>
                                                    <li>Walk Score: {{ $neighborhood->walk_score }}/100</li>
                                                    <li>Transit Score: {{ $neighborhood->transit_score }}/100</li>
                                                </ul>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold">Schools</h3>
                                                <ul class="list-disc list-inside">
                                                    @foreach ($neighborhood->schools as $school)
                                                        <li>{{ $school['name'] }} - {{ $school['rating'] }}/10</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold">Amenities</h3>
                                                <ul class="list-disc list-inside">
                                                    @foreach ($neighborhood->amenities as $amenity)
                                                        <li>{{ $amenity }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                        <p class="text-sm text-gray-500 mt-2">Last updated:
                                            {{ $neighborhood->last_updated->format('M d, Y H:i') }}</p>
                                    @else
                                        <ul class="list-disc list-inside grid grid-cols-2 gap-2">
                                            <li class="text-gray-500">No neighborhood details available</li>
                                        </ul>
                                    @endif
                                </div>

                                <hr class="my-2 md:my-2 border-gray-200 dark:border-gray-800" />
                                <div class="my-2">
                                    <span class="text-gray-500">Energy Efficiency</span>
                                    @if ($property->energy_rating && $property->energy_score)
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-xl mr-4"
                                                style="background-color: {{ $this->getEnergyRatingColor($property->energy_rating) }}">
                                                {{ $property->energy_rating }}
                                            </div>
                                            <div>
                                                <p class="font-semibold">Energy Efficiency Score:
                                                    {{ $property->energy_score }}/100</p>
                                                <p class="text-sm text-gray-600">Last updated:
                                                    {{ $property->energy_rating_date->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-600">Properties are rated from A (most efficient)
                                            to G
                                            (least efficient). Learn more about <a href="#"
                                                class="text-blue-600 hover:underline"
                                                @click="$dispatch('open-modal', 'energy-efficiency-info')">energy efficiency
                                                ratings</a>.</p>
                                    @else
                                        <ul class="list-disc list-inside grid grid-cols-2 gap-2">
                                            <li class="text-gray-500">No Energy efficiency details available</li>
                                        </ul>
                                    @endif
                                </div>
                                <hr class="my-2 md:my-2 border-gray-200 dark:border-gray-800" />
                                <div class="">
                                    <span class="text-gray-500">Branch/Team</span>
                                    <span
                                        class="bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $team->name ?? 'No team information available' }}</span>
                                </div>
                            </div>
                            <hr class="my-6 md:my-8 border-gray-200 dark:border-gray-800" />
                        </div>
                    </div>

                    <div class="w-full">
                        <p class="mb-6 text-gray-500 dark:text-gray-400">
                            {{ $property->description }}
                        </p>
                    </div>
                    <div class=""></div>
                    @auth
                        @livewire('property-review-form', ['propertyId' => $property->id])
                    @else
                        <p class="mt-4 text-gray-600">Please <a href="{{ route('login') }}"
                                class="text-blue-500 hover:underline">login</a> to leave a review.</p>
                    @endauth
                </div>
            </section>
        </section>
    @endsection
