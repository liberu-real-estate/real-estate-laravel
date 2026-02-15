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
                                <a href="#" title="" data-modal-target="scheduleViewingModal"
                                    data-modal-toggle="scheduleViewingModal"
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
