<div>
    <livewire:advanced-property-search />
    
    <div class="mt-8">
        @foreach($properties as $property)
            <div class="mb-4 p-4 border rounded">
                <h2 class="text-xl font-bold">{{ $property->title }}</h2>
                <p>{{ $property->description }}</p>
                <p>Location: {{ $property->location }}</p>
                <p>Price: ${{ number_format($property->price, 2) }}</p>
                <p>Bedrooms: {{ $property->bedrooms }}</p>
                <p>Bathrooms: {{ $property->bathrooms }}</p>
                <p>Area: {{ $property->area_sqft }} sqft</p>
                <p>Type: {{ ucfirst($property->property_type) }}</p>
                @if($property->features->isNotEmpty())
                    <p>Amenities: {{ $property->features->pluck('feature_name')->implode(', ') }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>