<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-semibold mb-4">Property Comparison</h2>

    <!-- Property Search -->
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="searchTerm" wire:keyup="searchProperties" placeholder="Search properties to compare" class="w-full p-2 border rounded">
        @if(count($searchResults) > 0)
            <div class="mt-2 bg-white shadow-md rounded-lg overflow-hidden">
                @foreach($searchResults as $result)
                    <div class="p-2 hover:bg-gray-100 cursor-pointer" wire:click="addProperty({{ $result->id }})">
                        {{ $result->title }}
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Comparison Table -->
    <div class="overflow-x-auto">
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2">Feature</th>
                    @foreach($properties as $property)
                        <th class="p-2">
                            {{ $property->title }}
                            <button wire:click="removeProperty({{ $property->id }})" class="ml-2 text-red-500">&times;</button>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($features as $feature)
                    <tr class="border-t">
                        <td class="p-2 font-semibold">{{ ucfirst(str_replace('_', ' ', $feature)) }}</td>
                        @foreach($properties as $property)
                            <td class="p-2">
                                @if($feature === 'price')
                                    {{ app(\App\Settings\GeneralSettings::class)->site_currency }}{{ number_format($property->$feature, 2) }}
                                @elseif($feature === 'area_sqft')
                                    {{ $property->$feature }} sqft
                                @else
                                    {{ $property->$feature }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>