<div>
@section('content')
<article class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <img src="{{ $property->getFirstMediaUrl('images') ?? asset('build/images/property-placeholder.png') }}" alt="{{ $property->title }}" class="w-full h-auto rounded-lg shadow-lg">
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
                @if($neighborhood)
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
                                @foreach($neighborhood->schools as $school)
                                    <li>{{ $school['name'] }} - {{ $school['rating'] }}/10</li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Amenities</h3>
                            <ul class="list-disc list-inside">
                                @foreach($neighborhood->amenities as $amenity)
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
                @if($property->energy_rating && $property->energy_score)
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

            @if(App\Providers\AppServiceProvider::isComponentEnabled('property-booking'))
                @livewire('property-booking', ['propertyId' => $property->id])
            @endif

            <div class="mt-8">
                <h2 class="text-2xl font-bold mb-4">Book a Valuation</h2>
                @if(App\Providers\AppServiceProvider::isComponentEnabled('valuation-booking'))
                    @livewire('valuation-booking')
                @endif
            </div>
            
            @if($isLettingsProperty)
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
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
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
</article>
@endsection
</div>
