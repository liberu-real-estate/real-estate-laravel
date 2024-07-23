<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="relative pb-2/3">
            @if($property->images->isNotEmpty())
                <img src="{{ $property->images->first()->image_url }}" alt="{{ $property->title }}" class="absolute h-full w-full object-cover">
            @else
                <div class="absolute h-full w-full bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-500">No image available</span>
                </div>
            @endif
        </div>
        <div class="p-6">
            <h1 class="text-3xl font-bold mb-4">{{ $property->title }}</h1>
            <p class="text-gray-600 mb-4">{{ $property->location }}</p>
            <div class="flex flex-wrap justify-between items-center mb-6">
                <span class="text-2xl font-bold text-green-600">${{ number_format($property->price, 2) }}</span>
                <div class="flex flex-wrap space-x-4 mt-2 sm:mt-0">
                    <span class="flex items-center"><i class="fas fa-bed mr-2"></i>{{ $property->bedrooms }} bed</span>
                    <span class="flex items-center"><i class="fas fa-bath mr-2"></i>{{ $property->bathrooms }} bath</span>
                    <span class="flex items-center"><i class="fas fa-ruler-combined mr-2"></i>{{ $property->area_sqft }} sqft</span>
                </div>
            </div>
            <p class="text-gray-700 mb-6">{{ $property->description }}</p>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Property Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p><strong>Category:</strong> {{ $property->category->name }}</p>
                        <p><strong>Year Built:</strong> {{ $property->year_built }}</p>
                        <p><strong>Property Type:</strong> {{ $property->property_type }}</p>
                    </div>
                    <div>
                        <p><strong>Status:</strong> {{ $property->status }}</p>
                        <p><strong>List Date:</strong> {{ $property->list_date->format('M d, Y') }}</p>
                        @if($property->sold_date)
                            <p><strong>Sold Date:</strong> {{ $property->sold_date->format('M d, Y') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Neighborhood: {{ $neighborhood->name }}</h2>
                <p>{{ $neighborhood->description }}</p>
            </div>

            @if($property->virtual_tour_url)
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-2">Virtual Tour</h2>
                    <div class="aspect-w-16 aspect-h-9">
                        @if(Str::endsWith($property->virtual_tour_url, ['.jpg', '.jpeg', '.png']))
                            <img src="{{ Storage::url($property->virtual_tour_url) }}" alt="Virtual Tour" class="object-cover rounded-lg">
                        @elseif(Str::endsWith($property->virtual_tour_url, '.mp4'))
                            <video controls class="w-full">
                                <source src="{{ Storage::url($property->virtual_tour_url) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <iframe src="{{ $property->virtual_tour_url }}" frameborder="0" allowfullscreen class="w-full h-full"></iframe>
                        @endif
                    </div>
                </div>
            @endif

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Book a Viewing</h2>
                <p class="mb-2">Available dates for viewing:</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                    @foreach($availableDates as $date)
                        <div class="bg-green-100 text-green-800 text-center py-2 rounded">
                            {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('property.book', ['property' => $property->id]) }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Schedule a Viewing</a>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Apply for Tenancy</h2>
                <button wire:click="createTenancyApplication" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Start Tenancy Application</button>
            </div>
        </div>
    </div>
</div>
