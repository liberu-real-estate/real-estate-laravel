<div>
    <form wire:submit.prevent="search">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label for="search">Search</label>
                <input type="text" id="search" wire:model="search" placeholder="Search properties...">
            </div>
            <div>
                <label for="minPrice">Min Price</label>
                <input type="number" id="minPrice" wire:model="minPrice">
            </div>
            <div>
                <label for="maxPrice">Max Price</label>
                <input type="number" id="maxPrice" wire:model="maxPrice">
            </div>
            <div>
                <label for="minBedrooms">Min Bedrooms</label>
                <input type="number" id="minBedrooms" wire:model="minBedrooms">
            </div>
            <div>
                <label for="maxBedrooms">Max Bedrooms</label>
                <input type="number" id="maxBedrooms" wire:model="maxBedrooms">
            </div>
            <div>
                <label for="minBathrooms">Min Bathrooms</label>
                <input type="number" id="minBathrooms" wire:model="minBathrooms">
            </div>
            <div>
                <label for="maxBathrooms">Max Bathrooms</label>
                <input type="number" id="maxBathrooms" wire:model="maxBathrooms">
            </div>
            <div>
                <label for="minArea">Min Area (sqft)</label>
                <input type="number" id="minArea" wire:model="minArea">
            </div>
            <div>
                <label for="maxArea">Max Area (sqft)</label>
                <input type="number" id="maxArea" wire:model="maxArea">
            </div>
            <div>
                <label for="propertyType">Property Type</label>
                <select id="propertyType" wire:model="propertyType">
                    <option value="">All Types</option>
                    @foreach($propertyTypes as $type)
                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Amenities</label>
                @foreach($amenities as $amenity)
                    <div>
                        <input type="checkbox" id="{{ $amenity }}" value="{{ $amenity }}" wire:model="selectedAmenities">
                        <label for="{{ $amenity }}">{{ $amenity }}</label>
                    </div>
                @endforeach
            </div>
        </div>
        <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Search</button>
    </form>
</div>