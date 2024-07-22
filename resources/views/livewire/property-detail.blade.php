<div class="flex justify-between items-center">
    <a href="{{ route('contact.show') }}" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition duration-200">Contact Agent</a>
    @livewire('property-booking', ['propertyId' => $property->id])
</div>