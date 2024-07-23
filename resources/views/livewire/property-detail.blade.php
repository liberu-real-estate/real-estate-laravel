
            @endif
        </div>
        <div class="p-6">
            <h1 class="text-3xl font-bold mb-4">{{ $property->title }}</h1>
            <p class="text-gray-600 mb-4">{{ $property->location }}</p>
            <div class="flex justify-between items-center mb-6">
                <span class="text-2xl font-bold text-green-600">${{ number_format($property->price, 2) }}</span>
                <div class="flex space-x-4">
                    <span class="flex items-center"><i class="fas fa-bed mr-2"></i>{{ $property->bedrooms }} bed</span>
                    <span class="flex items-center"><i class="fas fa-bath mr-2"></i>{{ $property->bathrooms }} bath</span>
                    <span class="flex items-center"><i class="fas fa-ruler-combined mr-2"></i>{{ $property->area_sqft }} sqft</span>
                </div>
            </div>
            <p class="text-gray-700 mb-6">{{ $property->description }}</p>
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
                <h2
