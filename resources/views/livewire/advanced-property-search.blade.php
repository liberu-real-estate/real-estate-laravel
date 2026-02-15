<div>
    <form wire:submit.prevent="search" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" id="search" wire:model.live.debounce.300ms="search" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="postalCode" class="block text-sm font-medium text-gray-700">Postal Code</label>
                <input type="text" id="postalCode" wire:model.live.debounce.300ms="postalCode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('postalCode') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
            <div>
                <label for="minPrice" class="block text-sm font-medium text-gray-700">Min Price</label>
                <input type="number" id="minPrice" wire:model="minPrice" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="maxPrice" class="block text-sm font-medium text-gray-700">Max Price</label>
                <input type="number" id="maxPrice" wire:model="maxPrice" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="bedrooms" class="block text-sm font-medium text-gray-700">Bedrooms</label>
                <select id="bedrooms" wire:model="minBedrooms" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="0">Any</option>
                    <option value="1">1+</option>
                    <option value="2">2+</option>
                    <option value="3">3+</option>
                    <option value="4">4+</option>
                </select>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Search Properties
            </button>
        </div>
    </form>

    <div class="mt-4">
        <label for="sortBy" class="block text-sm font-medium text-gray-700">Sort By</label>
        <select id="sortBy" wire:model="sortBy" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <option value="created_at">Newest</option>
            <option value="price_asc">Price (Low to High)</option>
            <option value="price_desc">Price (High to Low)</option>
            <option value="bedrooms">Bedrooms</option>
        </select>
    </div>

    <div class="mt-8">
        <x-property-map />
    </div>

    <!-- ... (keep existing property list display) ... -->
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('propertiesUpdated', function (properties) {
            // This event is emitted when the properties are updated
            // It will trigger the map update in the property-map component
        });
    });
</script>
@endpush
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
            <div>
                <label for="yearBuilt" class="block text-sm font-medium text-gray-700">Year Built (After)</label>
                <input type="number" id="yearBuilt" wire:model="yearBuilt" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" wire:model="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Statuses</option>
                    <option value="For Sale">For Sale</option>
                    <option value="Sold">Sold</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
            
            <!-- Enhanced Filters -->
            <div>
                <label for="energyRating" class="block text-sm font-medium text-gray-700">Energy Rating</label>
                <select id="energyRating" wire:model="energyRating" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Ratings</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                    <option value="F">F</option>
                    <option value="G">G</option>
                </select>
            </div>
            <div>
                <label for="minEnergyScore" class="block text-sm font-medium text-gray-700">Min Energy Score</label>
                <input type="number" id="minEnergyScore" wire:model="minEnergyScore" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="0-100">
            </div>
            <div>
                <label for="minWalkabilityScore" class="block text-sm font-medium text-gray-700">Min Walkability Score</label>
                <input type="number" id="minWalkabilityScore" wire:model="minWalkabilityScore" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="0-100">
            </div>
            <div>
                <label for="minTransitScore" class="block text-sm font-medium text-gray-700">Min Transit Score</label>
                <input type="number" id="minTransitScore" wire:model="minTransitScore" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="0-100">
            </div>
            <div>
                <label for="minBikeScore" class="block text-sm font-medium text-gray-700">Min Bike Score</label>
                <input type="number" id="minBikeScore" wire:model="minBikeScore" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="0-100">
            </div>
            <div>
                <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                <select id="country" wire:model="country" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Countries</option>
                    <option value="UK">United Kingdom</option>
                    <option value="US">United States</option>
                    <option value="FR">France</option>
                    <option value="DE">Germany</option>
                    <option value="ES">Spain</option>
                </select>
            </div>
            <div>
                <label for="featuredOnly" class="flex items-center">
                    <input type="checkbox" id="featuredOnly" wire:model="featuredOnly" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm font-medium text-gray-700">Featured Properties Only</span>
                </label>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Search Properties
            </button>
        </div>
    </form>

    <div class="mt-4">
        <label for="sortBy" class="block text-sm font-medium text-gray-700">Sort By</label>
        <select id="sortBy" wire:model="sortBy" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <option value="created_at">Newest</option>
            <option value="price_asc">Price (Low to High)</option>
            <option value="price_desc">Price (High to Low)</option>
            <option value="bedrooms">Bedrooms</option>
        </select>
    </div>

    <div class="mt-8">
        <x-property-map />
    </div>

    <!-- ... (keep existing property list display) ... -->
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('propertiesUpdated', function (properties) {
            // This event is emitted when the properties are updated
            // It will trigger the map update in the property-map component
        });
    });
</script>
@endpush
