<div>
    <form wire:submit.prevent>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" id="search" wire:model.debounce.300ms="search" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="minPrice" class="block text-sm font-medium text-gray-700">Min Price</label>
                <input type="number" id="minPrice" wire:model="minPrice" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="maxPrice" class="block text-sm font-medium text-gray-700">Max Price</label>
                <input type="number" id="maxPrice" wire:model="maxPrice" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="minBedrooms" class="block text-sm font-medium text-gray-700">Min Bedrooms</label>
                <input type="number" id="minBedrooms" wire:model="minBedrooms" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="maxBedrooms" class="block text-sm font-medium text-gray-700">Max Bedrooms</label>
                <input type="number" id="maxBedrooms" wire:model="maxBedrooms" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="propertyType" class="block text-sm font-medium text-gray-700">Property Type</label>
                <select id="propertyType" wire:model="propertyType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Types</option>
                    <option value="apartment">Apartment</option>
                    <option value="house">House</option>
                    <option value="condo">Condo</option>
                </select>
            </div>
        </div>
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Amenities</label>
            <div class="mt-2 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                @foreach($amenities as $amenity)
                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model="selectedAmenities" value="{{ $amenity }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2">{{ $amenity }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </form>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($properties as $property)
            <div class="border rounded-lg shadow-sm p-4">
                <h3 class="text-lg font-semibold">{{ $property->title }}</h3>
                <p class="mt-2">Price: ${{ number_format($property->price) }}</p>
                <p>Bedrooms: {{ $property->bedrooms }}</p>
                <p>Bathrooms: {{ $property->bathrooms }}</p>
                <p>Type: {{ ucfirst($property->property_type) }}</p>
                <p class="mt-2">Features: {{ $property->features->pluck('feature_name')->implode(', ') }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $properties->links() }}
    </div>
</div>