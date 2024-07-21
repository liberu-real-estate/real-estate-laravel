<div class="container mx-auto px-4 py-8">
@section('content')
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
                <h2 class="text-xl font-semibold mb-2">Amenities</h2>
                <ul class="grid grid-cols-2 gap-2">
                    @foreach($property->features as $feature)
                        <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i>{{ $feature->feature_name }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="flex justify-between items-center">
                <button class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition duration-200">Contact Agent</button>
                <button class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition duration-200">Schedule Viewing</button>
            </div>
        </div>
    </div>
@endsection
</div>
