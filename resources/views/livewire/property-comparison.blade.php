<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-semibold mb-4">Property Comparison</h2>
    <div class="mb-4">
        <input type="text" id="propertySearch" class="w-full p-2 border rounded" placeholder="Search for a property to add...">
    </div>
    <div class="overflow-x-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse($properties as $property)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-xl font-semibold">{{ $property->title }}</h3>
                            <button wire:click="removeProperty({{ $property->id }})" class="text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        @foreach($features as $feature)
                            <div class="mb-2">
                                <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $feature)) }}:</span>
                                <span class="@if($loop->first) text-blue-600 font-bold @endif">
                                    @if($feature === 'price')
                                        {{ \App\Helpers\SiteSettingsHelper::getCurrency() }}{{ number_format($property->$feature, 2) }}
                                    @elseif($feature === 'area_sqft')
                                        {{ $property->$feature }} sqft
                                    @else
                                        {{ $property->$feature }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500">
                    No properties added for comparison yet. Use the search bar above to add properties.
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:load', function () {
    const propertySearch = document.getElementById('propertySearch');
    let debounceTimer;

    propertySearch.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            fetch(`/api/properties/search?q=${this.value}`)
                .then(response => response.json())
                .then(data => {
                    // Clear previous results
                    while (propertySearch.nextSibling) {
                        propertySearch.nextSibling.remove();
                    }

                    // Create and append new results
                    const resultsContainer = document.createElement('div');
                    resultsContainer.className = 'absolute z-10 bg-white border rounded mt-1 w-full';
                    data.forEach(property => {
                        const propertyElement = document.createElement('div');
                        propertyElement.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                        propertyElement.textContent = property.title;
                        propertyElement.addEventListener('click', () => {
                            Livewire.emit('addProperty', property.id);
                            propertySearch.value = '';
                            resultsContainer.remove();
                        });
                        resultsContainer.appendChild(propertyElement);
                    });
                    propertySearch.parentNode.insertBefore(resultsContainer, propertySearch.nextSibling);
                });
        }, 300);
    });
});
</script>