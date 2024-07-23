<div class="container mx-auto px-4 py-8">
@section('content')
    <h1 class="text-3xl font-bold mb-8">Property Listings</h1>

    <div class="mb-6">
        <label for="category" class="block text-sm font-medium text-gray-700">Filter by Category</label>
        <select wire:model="selectedCategory" id="category" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    @if(count($properties) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($properties as $property)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden transition-transform duration-300 hover:scale-105">
                    <img src="{{ $property->images->first()->url ?? asset('build/images/property-placeholder.png') }}" alt="{{ $property->title }}" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $property->title }}</h3>
                        <p class="text-gray-600 mb-2">{{ $property->location }}</p>
                        <p class="text-sm text-gray-500 mb-4">Category: {{ $property->category->name ?? 'Uncategorized' }}</p>
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
