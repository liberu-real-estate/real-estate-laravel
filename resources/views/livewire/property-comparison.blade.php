<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-semibold mb-4">Property Comparison</h2>
    <div class="overflow-x-auto">
        <table class="w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr>
                    <th class="px-4 py-2 bg-gray-100">Feature</th>
                    @foreach($properties as $property)
                        <th class="px-4 py-2 bg-gray-100">{{ $property->title }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($features as $feature)
                    <tr>
                        <td class="px-4 py-2 font-semibold">{{ ucfirst(str_replace('_', ' ', $feature)) }}</td>
                        @foreach($properties as $property)
                            <td class="px-4 py-2 @if($loop->first) bg-blue-100 @endif">
                                @if($feature === 'price')
                                    ${{ number_format($property->$feature, 2) }}
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