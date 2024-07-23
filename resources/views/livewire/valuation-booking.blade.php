<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="bookValuation" class="space-y-4">
        <div>
            <label for="selectedDate" class="block text-sm font-medium text-gray-700">Select a Date</label>
            <select id="selectedDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" wire:model="selectedDate">
                <option value="">Select a date</option>
                @foreach($availableDates as $date)
                    <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</option>
                @endforeach
            </select>
            @error('selectedDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="userName" class="block text-sm font-medium text-gray-700">Your Name</label>
            <input type="text" id="userName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" wire:model="userName">
            @error('userName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="userContact" class="block text-sm font-medium text-gray-700">Contact Information</label>
            <input type="text" id="userContact" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" wire:model="userContact">
            @error('userContact') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="propertyAddress" class="block text-sm font-medium text-gray-700">Property Address</label>
            <input type="text" id="propertyAddress" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" wire:model="propertyAddress">
            @error('propertyAddress') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
            <textarea id="notes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" wire:model="notes" rows="3"></textarea>
        </div>

        <div>
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                Schedule Valuation
            </button>
        </div>
    </form>
</div>