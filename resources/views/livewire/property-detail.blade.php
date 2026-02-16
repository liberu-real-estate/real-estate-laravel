<div>
    @php
        $currency = app(\App\Settings\GeneralSettings::class)->site_currency;
    @endphp
    @section('content')
        <section>
            <section class="py-8 bg-white md:py-16 dark:bg-gray-900 antialiased">
                <div class="max-w-(--breakpoint-xl) px-4 mx-auto 2xl:px-0">
                    <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
                        <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
                            <img src="{{ $property->getFirstMediaUrl('images') ?? asset('build/images/property-placeholder.png') }}"
                                alt="{{ $property->title }}"
                                onerror="this.onerror=null; this.src='{{ asset('build/images/property-placeholder.png') }}';"
                                class="w-full dark:hidden">
                            
                            @if($property->hasMedia('3d_models'))
                                <div class="mt-6">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                                        <svg class="w-5 h-5 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        3D Property Model
                                    </h3>
                                    <model-viewer 
                                        src="{{ $property->getFirstMediaUrl('3d_models') }}"
                                        alt="3D model of {{ $property->title }}"
                                        ar
                                        ar-modes="webxr scene-viewer quick-look"
                                        camera-controls
                                        touch-action="pan-y"
                                        auto-rotate
                                        shadow-intensity="1"
                                        class="w-full h-96 bg-gray-100 dark:bg-gray-800 rounded-lg"
                                        style="--poster-color: #f3f4f6;">
                                    </model-viewer>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                                        </svg>
                                        Drag to rotate • Pinch to zoom • Tap AR icon for augmented reality
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 sm:mt-8 lg:mt-0">
                            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
                                {{ $property->title }}
                            </h1>
                            <div class="mt-4 sm:items-center sm:gap-4 sm:flex">
                                <p class="text-2xl font-extrabold text-gray-900 sm:text-3xl dark:text-white">
                                    {{ $currency . ' ' . number_format($property->price, 2) }}
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
                                <button wire:click="toggleFavorite" 
                                    class="flex items-center justify-center py-2.5 px-5 text-sm font-medium focus:outline-none rounded-lg border focus:z-10 focus:ring-4 
                                    {{ $isFavorited 
                                        ? 'text-white bg-red-600 border-red-600 hover:bg-red-700 focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800' 
                                        : 'text-gray-900 bg-white border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700' 
                                    }}"
                                    title="{{ $isFavorited ? 'Remove from wishlist' : 'Add to wishlist' }}">
                                    <svg class="w-5 h-5 -ms-2 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z" />
                                    </svg>
                                    {{ $isFavorited ? 'In Wishlist' : 'Add to Wishlist' }}
                                </button>

                                <a href="#" title="" data-modal-target="scheduleViewingModal"
                                    data-modal-toggle="scheduleViewingModal"
                                    class="flex items-center justify-center py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 mt-4 sm:mt-0"
                                    role="button">
                                    <svg class="w-5 h-5 -ms-2 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Schedule a viewing
                                </a>

                                <a href="#" title="" data-modal-target="bookValuationModal"
                                    data-modal-toggle="bookValuationModal"
                                    class="text-white mt-4 sm:mt-0 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 flex items-center justify-center"
                                    role="button">
                                    <svg class="w-5 h-5 -ms-2 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                                    </svg>
                                    Book valuation
                                </a>

                                <a href="{{ route('property.valuation', ['propertyId' => $property->id]) }}" 
                                    class="text-white mt-4 sm:mt-0 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 flex items-center justify-center"
                                    role="button">
                                    <svg class="w-5 h-5 -ms-2 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    AI Valuation
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
                                        
                                        <!-- Neighborhood Reviews Section -->
                                        <div class="mt-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Neighborhood Reviews</h3>
                                                @if($neighborhoodReviews && $neighborhoodReviews->count() > 0)
                                                    <div class="flex items-center gap-2">
                                                        <div class="flex items-center">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <svg class="w-5 h-5 {{ $i <= round($neighborhoodAverageRating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                                                </svg>
                                                            @endfor
                                                        </div>
                                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ number_format($neighborhoodAverageRating, 1) }} ({{ $neighborhoodReviews->count() }} {{ Str::plural('review', $neighborhoodReviews->count()) }})</span>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            @if($neighborhoodReviews && $neighborhoodReviews->count() > 0)
                                                <div class="space-y-4 mb-4">
                                                    @foreach($neighborhoodReviews->take(3) as $review)
                                                        <div class="bg-white dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                                            <div class="flex items-start justify-between mb-2">
                                                                <div>
                                                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $review->title }}</h4>
                                                                    <div class="flex items-center gap-2 mt-1">
                                                                        <div class="flex">
                                                                            @for ($i = 1; $i <= 5; $i++)
                                                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24">
                                                                                    <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                                                                </svg>
                                                                            @endfor
                                                                        </div>
                                                                        <span class="text-sm text-gray-600 dark:text-gray-400">by {{ $review->user->name ?? 'Anonymous' }}</span>
                                                                        <span class="text-sm text-gray-500">• {{ $review->created_at->diffForHumans() }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $review->comment }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">No reviews yet. Be the first to review this neighborhood!</p>
                                            @endif
                                            
                                            <!-- Neighborhood Review Form -->
                                            @auth
                                                @livewire('neighborhood-review-form', ['neighborhoodId' => $neighborhood->id])
                                            @else
                                                <p class="mt-4 text-gray-600 dark:text-gray-400">Please <a href="{{ route('login') }}" class="text-primary-600 hover:underline">login</a> to leave a neighborhood review.</p>
                                            @endauth
                                        </div>
                                    @else
                                        <ul class="list-disc list-inside grid grid-cols-2 gap-2">
                                            <li class="text-gray-500">No neighborhood details available</li>
                                        </ul>
                                    @endif
                                </div>

                                <hr class="my-2 md:my-2 border-gray-200 dark:border-gray-800" />
                                <div class="my-2">
                                    <span class="text-gray-500 font-semibold">Walkability Scores</span>
                                    @if ($property->walkability_score)
                                        <div class="mt-3 space-y-3">
                                            <!-- Walk Score -->
                                            <div class="flex items-center">
                                                <div class="w-16 h-16 rounded-lg flex flex-col items-center justify-center text-white font-bold mr-4"
                                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                    <span class="text-2xl">{{ $property->walkability_score }}</span>
                                                    <span class="text-xs">Walk</span>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $property->walkability_description }}</p>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">Daily errands and amenities</p>
                                                </div>
                                            </div>

                                            <!-- Transit Score -->
                                            @if ($property->transit_score)
                                            <div class="flex items-center">
                                                <div class="w-16 h-16 rounded-lg flex flex-col items-center justify-center text-white font-bold mr-4"
                                                    style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                                    <span class="text-2xl">{{ $property->transit_score }}</span>
                                                    <span class="text-xs">Transit</span>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $property->transit_description }}</p>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">Public transportation options</p>
                                                </div>
                                            </div>
                                            @endif

                                            <!-- Bike Score -->
                                            @if ($property->bike_score)
                                            <div class="flex items-center">
                                                <div class="w-16 h-16 rounded-lg flex flex-col items-center justify-center text-white font-bold mr-4"
                                                    style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                                    <span class="text-2xl">{{ $property->bike_score }}</span>
                                                    <span class="text-xs">Bike</span>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $property->bike_description }}</p>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">Bike lanes and biking infrastructure</p>
                                                </div>
                                            </div>
                                            @endif

                                            <p class="text-xs text-gray-500 mt-2">
                                                Last updated: {{ $property->walkability_updated_at ? $property->walkability_updated_at->format('M d, Y') : 'N/A' }}
                                            </p>
                                        </div>
                                    @else
                                        <p class="text-gray-500 mt-2">Walkability scores not available for this property</p>
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
                                                @click="$dispatch('open-modal', 'energy-efficiency-info')">energy
                                                efficiency
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
                                
                                <!-- Investment Analytics Section -->
                                @if($investmentAnalytics)
                                <hr class="my-2 md:my-2 border-gray-200 dark:border-gray-800" />
                                <div class="my-2">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-gray-500 font-semibold">Investment Analytics</span>
                                        <span class="text-xs text-gray-400">AI-Powered Insights</span>
                                    </div>
                                    
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-lg p-4 space-y-4">
                                        <!-- Investment Prediction -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Predicted ROI</h4>
                                                <p class="text-3xl font-bold {{ $investmentAnalytics['prediction']['predicted_roi'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ number_format($investmentAnalytics['prediction']['predicted_roi'], 2) }}%
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">Expected return over 5 years</p>
                                            </div>
                                            
                                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Risk Score</h4>
                                                <div class="flex items-center">
                                                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                                                        {{ number_format($investmentAnalytics['prediction']['risk_score'], 1) }}/10
                                                    </p>
                                                    <div class="ml-3 flex-1">
                                                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($investmentAnalytics['prediction']['risk_score'] / 10) * 100 }}%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">Lower is better</p>
                                            </div>
                                        </div>

                                        <!-- Cash Flow Analysis -->
                                        @if(isset($investmentAnalytics['cash_flow_analysis']))
                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Cash Flow Projection</h4>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                                <div>
                                                    <p class="text-xs text-gray-500">Est. Annual Rent</p>
                                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                                        {{ $currency }}{{ number_format($investmentAnalytics['cash_flow_analysis']['estimated_annual_rent'], 0) }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Est. Expenses</p>
                                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                                        {{ $currency }}{{ number_format($investmentAnalytics['cash_flow_analysis']['estimated_expenses'], 0) }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Net Cash Flow</p>
                                                    <p class="text-lg font-semibold text-green-600">
                                                        {{ $currency }}{{ number_format($investmentAnalytics['cash_flow_analysis']['net_cash_flow'], 0) }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Cash-on-Cash</p>
                                                    <p class="text-lg font-semibold text-blue-600">
                                                        {{ number_format($investmentAnalytics['cash_flow_analysis']['cash_on_cash_return'], 2) }}%
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Market Position -->
                                        @if(isset($investmentAnalytics['market_position']))
                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Market Position</h4>
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $this->getPositionBadgeClass($investmentAnalytics['market_position']['position']) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $investmentAnalytics['market_position']['position'])) }}
                                                </span>
                                                <span class="text-sm {{ $investmentAnalytics['market_position']['price_vs_market'] < 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                                    {{ $investmentAnalytics['market_position']['price_vs_market'] >= 0 ? '+' : '' }}{{ number_format($investmentAnalytics['market_position']['price_vs_market'], 1) }}% vs market
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $investmentAnalytics['market_position']['competitive_advantage'] }}
                                            </p>
                                        </div>
                                        @endif
                                        
                                        <!-- Investment Simulator Link -->
                                        <div class="text-center pt-2">
                                            <button wire:click="toggleInvestmentSimulation" 
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-150 ease-in-out">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                {{ $showInvestmentSimulation ? 'Hide' : 'Show' }} Advanced Investment Simulator
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <hr class="my-6 md:my-8 border-gray-200 dark:border-gray-800" />
                            
                            <!-- Property Tax Estimator Section -->
                            <div class="mt-6">
                                @livewire('property-tax-estimator', ['property' => $property])
                            </div>
                        </div>
                    </div>

                    <div class="w-full">
                        <p class="mb-6 text-gray-500 dark:text-gray-400">
                            {{ $property->description }}
                        </p>
                    </div>

                    {{-- 3D Model Viewer Section --}}
                    @if($property->model_3d_url)
                        <div class="w-full mt-8 mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">3D Property Model</h2>
                            <x-model-3d-viewer :modelUrl="$property->model_3d_url" :propertyTitle="$property->title" />
                        </div>
                    @endif

                    {{-- Property History Section --}}
                    @if($propertyHistory->count() > 0 || $priceHistory->count() > 0 || $salesHistory->count() > 0)
                        <div class="w-full mt-8 mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Property History</h2>
                            
                            {{-- Price History --}}
                            @if($priceHistory->count() > 0)
                                <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                        </svg>
                                        Price Changes
                                    </h3>
                                    <div class="space-y-3">
                                        @foreach($priceHistory as $history)
                                            <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $history->event_date->format('M d, Y') }}</p>
                                                    <p class="text-gray-900 dark:text-white font-medium">
                                                        {{ app(\App\Settings\GeneralSettings::class)->site_currency }} {{ number_format($history->old_price, 2) }}
                                                        <svg class="w-4 h-4 inline mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                        </svg>
                                                        {{ app(\App\Settings\GeneralSettings::class)->site_currency }} {{ number_format($history->new_price, 2) }}
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    @php
                                                        $change = $history->getPriceChangePercentage();
                                                        $isIncrease = $change >= 0;
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isIncrease ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                                        {{ $isIncrease ? '+' : '' }}{{ number_format($change, 2) }}%
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Sales History --}}
                            @if($salesHistory->count() > 0)
                                <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Past Sales
                                    </h3>
                                    <div class="space-y-3">
                                        @foreach($salesHistory as $sale)
                                            <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                                                <div>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $sale->event_date->format('M d, Y') }}</p>
                                                    <p class="text-gray-900 dark:text-white">{{ $sale->description }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-lg font-bold text-green-600">
                                                        {{ app(\App\Settings\GeneralSettings::class)->site_currency }} {{ number_format($sale->new_price, 2) }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- General History Timeline --}}
                            @if($propertyHistory->count() > 0)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Activity Timeline
                                    </h3>
                                    <div class="space-y-4">
                                        @foreach($propertyHistory as $event)
                                            <div class="flex gap-4">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 rounded-full flex items-center justify-center
                                                        {{ $event->event_type === 'sale' ? 'bg-green-100 dark:bg-green-800' : 
                                                           ($event->event_type === 'price_change' ? 'bg-blue-100 dark:bg-blue-800' : 
                                                           ($event->event_type === 'status_change' ? 'bg-yellow-100 dark:bg-yellow-800' : 'bg-gray-100 dark:bg-gray-700')) }}">
                                                        @if($event->event_type === 'sale')
                                                            <svg class="w-5 h-5 text-green-600 dark:text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        @elseif($event->event_type === 'price_change')
                                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                                            </svg>
                                                        @elseif($event->event_type === 'status_change')
                                                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $event->event_date->format('M d, Y') }}</p>
                                                    <p class="text-gray-900 dark:text-white font-medium">{{ $event->description }}</p>
                                                    @if($event->event_type === 'update' && $event->changes)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                            Updated fields: {{ implode(', ', array_keys($event->changes)) }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    
                    {{-- Community Events Calendar Section --}}
                    @if($communityEvents->count() > 0)
                        <div class="w-full mt-8 mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Community Events Calendar
                            </h2>
                            
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                                <!-- Calendar Navigation -->
                                <div class="flex items-center justify-between mb-6">
                                    <button wire:click="changeMonth('prev')" 
                                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->format('F Y') }}
                                    </h3>
                                    <button wire:click="changeMonth('next')" 
                                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Events List -->
                                <div class="space-y-4">
                                    @foreach($communityEvents as $event)
                                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                                            <div class="flex items-start space-x-4">
                                                <!-- Event Date Badge -->
                                                <div class="flex-shrink-0 w-16 h-16 bg-blue-600 rounded-lg flex flex-col items-center justify-center text-white">
                                                    <span class="text-xs font-semibold uppercase">{{ $event->event_date->format('M') }}</span>
                                                    <span class="text-2xl font-bold">{{ $event->event_date->format('d') }}</span>
                                                </div>
                                                
                                                <!-- Event Details -->
                                                <div class="flex-1">
                                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                                        {{ $event->title }}
                                                    </h4>
                                                    
                                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ $event->event_date->format('g:i A') }}
                                                        @if($event->end_date)
                                                            - {{ $event->end_date->format('g:i A') }}
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        {{ $event->location }}
                                                        @if(isset($event->distance_from_property))
                                                            <span class="ml-2 text-xs text-blue-600 dark:text-blue-400">
                                                                ({{ number_format($event->distance_from_property, 1) }} km away)
                                                            </span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($event->category)
                                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mb-2">
                                                            {{ ucfirst($event->category) }}
                                                        </span>
                                                    @endif
                                                    
                                                    @if($event->description)
                                                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-2">
                                                            {{ Str::limit($event->description, 150) }}
                                                        </p>
                                                    @endif
                                                    
                                                    @if($event->organizer)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                                            Organized by: {{ $event->organizer }}
                                                        </p>
                                                    @endif
                                                    
                                                    @if($event->website_url)
                                                        <a href="{{ $event->website_url }}" target="_blank" 
                                                           class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mt-2">
                                                            Learn more
                                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                                        Showing {{ $communityEvents->count() }} upcoming events within 10 km of this property
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Interactive Floor Plan -->
                    <x-floor-plan-viewer :floor-plan-data="$property->floor_plan_data" />
                    

                    {{-- Property Video Section --}}
                    @php
                        $video = $property->getFirstMedia('videos');
                    @endphp
                    @if($video)
                        <div class="w-full mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Property Video</h2>
                            {{-- 16:9 aspect ratio container (9/16 * 100 = 56.25%) --}}
                            <div class="relative w-full" style="padding-bottom: 56.25%;">
                                <video 
                                    class="absolute top-0 left-0 w-full h-full rounded-lg shadow-lg"
                                    controls
                                    preload="metadata"
                                    aria-label="Property video tour for {{ $property->title }}">
                                    <source src="{{ $video->getUrl() }}" type="{{ $video->mime_type }}">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    
                    <!-- Advanced Investment Simulator -->
                    @if($showInvestmentSimulation)
                    <div class="w-full mb-8">
                        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6">
                            @livewire('investment-analysis-component', ['property' => $property])
                        </div>
                    </div>
                    @endif
                    

                    @if($property->getFirstMediaUrl('videos'))
                    <div class="w-full mb-8">
                        <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Property Video</h2>
                        <div class="max-w-4xl mx-auto">
                            <video controls class="w-full rounded-lg shadow-lg" controlsList="nodownload" aria-label="Property video">
                                <source src="{{ $property->getFirstMediaUrl('videos') }}">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>
                    @endif

                    <div class=""></div>
                    @auth
                        @livewire('property-review-form', ['propertyId' => $property->id])
                    @else
                        <p class="mt-4 text-gray-600">Please <a href="{{ route('login') }}"
                                class="text-blue-500 hover:underline">login</a> to leave a review.</p>
                    @endauth
                </div>
            </section>

            <div id="bookValuationModal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-2xl max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Book valution
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-hide="bookValuationModal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <div class="p-4 md:p-5 space-y-4">
                            @if (false && App\Providers\AppServiceProvider::isComponentEnabled('valuation-booking'))
                                @livewire('valuation-booking')
                            @endif

                            @if ($isLettingsProperty)
                                <div class="mt-8">
                                    <a href="{{ route('tenancy.apply', $property->id) }}"
                                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                                        Apply for Tenancy
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button data-modal-hide="bookValuationModal" type="button"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Book
                                valution</button>
                            <button data-modal-hide="bookValuationModal" type="button"
                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Decline</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="scheduleViewingModal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-2xl max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <form wire:submit.prevent="bookViewing" class="space-y-4">
                            <div
                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Schedule viewing
                                </h3>
                                <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="scheduleViewingModal">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <div class="p-4 md:p-5 space-y-4">
                                @if (false && App\Providers\AppServiceProvider::isComponentEnabled('property-booking'))
                                    @livewire('property-booking', ['propertyId' => $property->id])
                                @endif

                                @if ($isLettingsProperty)
                                    <div class="mt-8">
                                        <a href="{{ route('tenancy.apply', $property->id) }}"
                                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                                            Apply for Tenancy
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div
                                class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button type="submit" data-modal-hide="scheduleViewingModal" type="button"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Schedule Viewing
                                </button>
                                <button data-modal-hide="scheduleViewingModal" type="button"
                                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Decline</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @endsection
