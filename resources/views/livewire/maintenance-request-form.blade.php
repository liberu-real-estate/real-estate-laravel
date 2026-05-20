<div>
    <h2 class="text-2xl font-semibold mb-4">Submit Maintenance Request</h2>
    <form wire:submit.prevent="submit">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" id="title" wire:model="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="property_id" class="block text-sm font-medium text-gray-700">Property</label>
            <select id="property_id" wire:model="property_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">Select a property</option>
                @foreach($properties as $property)
                    <option value="{{ $property->id }}">{{ $property->title }}</option>
                @endforeach
            </select>
            @error('property_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
            Submit Request
        </button>
    </form>
    @if (session()->has('message'))
        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-md">
            {{ session('message') }}
        </div>
    @endif
</div>