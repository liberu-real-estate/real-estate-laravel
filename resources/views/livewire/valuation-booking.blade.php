<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="bookValuation" class="space-y-4">
        <div>
            <label for="selectedDate" class="block text-sm font-medium text-gray-700">Select a Date for Valuation</label>
            <select id="selectedDate"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                wire:model="selectedDate">
                <option value="">Select a date</option>
                @foreach ($availableDates as $date)
                    <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</option>
                @endforeach
            </select>
            @error('selectedDate')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="userName" class="block text-sm font-medium text-gray-700">Your Name</label>
            <input type="text" id="userName"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                wire:model="userName">
            @error('userName')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="userContact" class="block text-sm font-medium text-gray-700">Contact Information</label>
            <input type="text" id="userContact"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                wire:model="userContact">
            @error('userContact')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="propertyType" class="block text-sm font-medium text-gray-700">Property Type</label>
            <select id="propertyType"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                wire:model="propertyType">
                <option value="">Select property type</option>
                <option value="house">House</option>
                <option value="apartment">Apartment</option>
                <option value="condo">Condo</option>
                <option value="townhouse">Townhouse</option>
            </select>
            @error('propertyType')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
            <input type="text" id="location"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                wire:model="location">
            @error('location')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="areaSqft" class="block text-sm font-medium text-gray-700">Area (sq ft)</label>
            <input type="number" id="areaSqft"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                wire:model="areaSqft">
            @error('areaSqft')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="bedrooms" class="block text-sm font-medium text-gray-700">Number of Bedrooms</label>
            <input type="number" id="bedrooms"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                wire:model="bedrooms">
            @error('bedrooms')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="bathrooms" class="block text-sm font-medium text-gray-700">Number of bathrooms</label>
            <input type="number" id="bathrooms"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                wire:model="bathrooms">
            @error('bathrooms')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

    </form>
</div>
