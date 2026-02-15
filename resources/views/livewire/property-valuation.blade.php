<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Neural Network Property Valuation</h1>
            <p class="text-gray-600">AI-powered property valuation using advanced machine learning algorithms</p>
        </div>

        @if($errorMessage)
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <span class="block sm:inline">{{ $errorMessage }}</span>
            </div>
        @endif

        @if($property)
            <!-- Property Summary Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-semibold mb-4">{{ $property->title }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Location</p>
                        <p class="font-semibold">{{ $property->location }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Property Type</p>
                        <p class="font-semibold capitalize">{{ str_replace('_', ' ', $property->property_type) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Current Price</p>
                        <p class="font-semibold text-lg">£{{ number_format($property->price, 2) }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    <div>
                        <p class="text-gray-600 text-sm">Bedrooms</p>
                        <p class="font-semibold">{{ $property->bedrooms }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Bathrooms</p>
                        <p class="font-semibold">{{ $property->bathrooms }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Area (sqft)</p>
                        <p class="font-semibold">{{ number_format($property->area_sqft) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Year Built</p>
                        <p class="font-semibold">{{ $property->year_built }}</p>
                    </div>
                </div>
            </div>

            <!-- Generate Valuation Button -->
            <div class="mb-6">
                <button 
                    wire:click="generateValuation" 
                    wire:loading.attr="disabled"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    @if(!Auth::check()) disabled @endif
                >
                    <span wire:loading.remove wire:target="generateValuation">
                        <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Generate AI Valuation
                    </span>
                    <span wire:loading wire:target="generateValuation">
                        <svg class="animate-spin inline-block w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Generating Valuation...
                    </span>
                </button>
                @if(!Auth::check())
                    <p class="text-sm text-gray-600 mt-2">Please <a href="/login" class="text-blue-600 hover:underline">login</a> to generate valuations</p>
                @endif
            </div>

            <!-- Valuation Report -->
            @if($showReport && $valuation)
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6 border-2 border-blue-200">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-2xl font-bold text-gray-900">Valuation Report</h3>
                        <button wire:click="closeReport" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Main Valuation Result -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6">
                        <div class="text-center">
                            <p class="text-gray-700 text-sm mb-2">Estimated Market Value</p>
                            <p class="text-4xl font-bold text-blue-600">£{{ number_format($valuation->estimated_value ?? 0, 2) }}</p>
                            <div class="mt-4">
                                <div class="flex justify-center items-center space-x-2">
                                    <span class="text-sm text-gray-600">Confidence Level:</span>
                                    <div class="flex items-center">
                                        <div class="w-32 bg-gray-200 rounded-full h-3 mr-2">
                                            <div class="bg-green-500 h-3 rounded-full" style="width: {{ $valuation->confidence_level ?? 0 }}%"></div>
                                        </div>
                                        <span class="font-semibold text-green-600">{{ $valuation->confidence_level ?? 0 }}%</span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    Accuracy: {{ $valuation->getValuationAccuracy() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Valuation Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Key Metrics -->
                        <div class="border rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Key Metrics</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Valuation Method:</span>
                                    <span class="font-semibold">Neural Network</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Valuation Date:</span>
                                    <span class="font-semibold">{{ $valuation->valuation_date?->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Valid Until:</span>
                                    <span class="font-semibold">{{ $valuation->valid_until?->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">{{ ucfirst($valuation->status) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Market Insights -->
                        <div class="border rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Market Insights</h4>
                            <div class="space-y-2">
                                @if(isset($valuation->location_factors['market_trend']))
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Market Trend:</span>
                                        <span class="font-semibold capitalize">{{ $valuation->location_factors['market_trend'] }}</span>
                                    </div>
                                @endif
                                @if(isset($valuation->comparable_properties['count']))
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Comparables Used:</span>
                                        <span class="font-semibold">{{ $valuation->comparable_properties['count'] }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Model Version:</span>
                                    <span class="font-semibold text-xs">v{{ $valuation->notes ? (preg_match('/v(\d+\.\d+\.\d+)/', $valuation->notes, $matches) ? $matches[1] : '1.0.0') : '1.0.0' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature Importance -->
                    @if(isset($valuation->comparable_properties['feature_importance']) && count($valuation->comparable_properties['feature_importance']) > 0)
                        <div class="border rounded-lg p-4 mb-6">
                            <h4 class="font-semibold text-gray-900 mb-3">Top Value Factors</h4>
                            <div class="space-y-3">
                                @foreach($valuation->comparable_properties['feature_importance'] as $feature => $importance)
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm text-gray-700 capitalize">{{ str_replace('_', ' ', $feature) }}</span>
                                            <span class="text-sm font-semibold text-blue-600">{{ number_format($importance, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $importance }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Prediction Factors -->
                    @if(isset($valuation->location_factors['prediction_factors']) && count($valuation->location_factors['prediction_factors']) > 0)
                        <div class="border rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Prediction Insights</h4>
                            <ul class="space-y-2">
                                @foreach($valuation->location_factors['prediction_factors'] as $factor)
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-gray-700 text-sm">{{ $factor }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Notes -->
                    @if($valuation->notes)
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-700"><strong>Notes:</strong> {{ $valuation->notes }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Valuation History -->
            @if(count($valuationHistory) > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold mb-4">Valuation History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimated Value</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confidence</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($valuationHistory as $hist)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $hist->valuation_date?->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            £{{ number_format($hist->estimated_value ?? 0, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $hist->confidence_level ?? 0 }}%"></div>
                                                </div>
                                                <span class="text-sm text-gray-700">{{ $hist->confidence_level ?? 0 }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $hist->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($hist->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <button 
                                                wire:click="viewValuation({{ $hist->id }})"
                                                class="text-blue-600 hover:text-blue-900 font-medium"
                                            >
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        @else
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                <p>Property not found. Please select a valid property to generate valuations.</p>
            </div>
        @endif
    </div>
</div>
