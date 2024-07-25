<div>
@section('content')
<article class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
<img src="{{ $property->getFirstMediaUrl('images') ?: asset('build/images/property-placeholder.png') }}" alt="{{ $property->title }}" class="w-full h-auto rounded-lg shadow-lg">
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
