<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-semibold mb-4">Property Comparison</h2>

    <div class="mb-4">
        <label for="property-select" class="block text-sm font-medium text-gray-700">Add property to compare:</label>
        <select id="property-select" wire:model="selectedProperty" wire:change="addProperty($event.target.value)" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            <option value="">Select a property</option>
            @foreach($availableProperties as $property)
                <option value="{{ $property->id }}">{{ $property->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="overflow-x-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($properties as $property)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-xl font-semibold">{{ $property->title }}</h3>
                            <button wire:click="removeProperty({{ $property->id }})" class="text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        @foreach($features as $feature)
                            <div class="mb-2">
                                <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $feature)) }}:</span>
                                <span class="@if($feature === 'price') text-blue-600 font-bold @endif">
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
            @endforeach
        </div>
    </div>
</div>