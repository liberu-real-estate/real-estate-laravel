<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-semibold mb-4">Property Comparison</h2>
    <div class="overflow-x-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($properties as $property)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">{{ $property->title }}</h3>
                        @foreach($features as $feature)
                            <div class="mb-2">
                                <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $feature)) }}:</span>
                                <span class="@if($loop->first) text-blue-600 font-bold @endif">
                                    @if($feature === 'price')
                                        ${{ number_format($property->$feature, 2) }}
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