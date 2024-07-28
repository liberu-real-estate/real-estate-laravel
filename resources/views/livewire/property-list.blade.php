
<div class="container mx-auto px-4 py-8">
@section('content')
    <h1 class="text-3xl font-bold mb-8">Property Listings</h1>

    @if(\App\Providers\AppServiceProvider::isComponentEnabled('property-search'))
        <div class="mb-8">
            <form action="{{ route('property.list') }}" method="GET" class="max-w-2xl mx-auto">
                <div class="flex flex-wrap -mx-2 mb-4">
                    <div class="w-full md:w-1/2 px-2 mb-4 md:mb-0">
                        <input type="text" name="search" placeholder="Search by location or property name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="w-full md:w-1/4 px-2 mb-4 md:mb-0">
                        <select name="property_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Types</option>
                            <option value="apartment">Apartment</option>
                            <option value="house">House</option>
                            <option value="condo">Condo</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/4 px-2">
                        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">Search</button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    @if(count($properties) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($properties as $property)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden transition-transform duration-300 hover:scale-105">
                    <img src="{{ $property->getFirstMediaUrl('images') ?: asset('build/images/property-placeholder.png') }}" alt="{{ $property->title }}" class="w-full h-auto rounded-lg shadow-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $property->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ $property->location }}</p>
                        <div class="text-sm text-gray-700 mb-4">
                            {{ Str::limit($property->description, 150) }}
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-blue-600">£{{ number_format($property->price) }}</span>
                            <a href="{{ route('property.detail', $property->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors duration-300">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $properties->links() }}
        </div>
    @else
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
            <p class="font-bold">No properties found</p>
            <p>Please try adjusting your search criteria or check back later for new listings.</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mt-4" role="alert">
            <p class="font-bold">Error</p>
            <p>{{ session('error') }}</p>
            @if(app()->environment('local'))
                <p class="mt-2 text-sm">
                    <strong>Debug info:</strong> {{ session('error_details') ?? 'No additional details available.' }}
                </p>
            @endif
        </div>
    @endif

    @if(app()->environment('local'))
        <div class="bg-gray-100 p-4 mt-4">
            <p class="font-bold">Debug Information:</p>
            <p>Total properties: {{ $properties->total() }}</p>
            <p>Current page: {{ $properties->currentPage() }}</p>
            <p>Last page: {{ $properties->lastPage() }}</p>
            <p>Properties on this page: {{ $properties->count() }}</p>
        </div>
    @endif
@endsection
</div>
        </div>
    @endif
@endsection
</div>
