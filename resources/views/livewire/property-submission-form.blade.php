<div>
    <h2 class="text-2xl font-bold mb-4">Submit a Property</h2>
    <form wire:submit.prevent="submit">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" id="title" wire:model="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
        </div>

        <!-- Add these new fields at the end of the form, before the submit button -->
        <div class="mb-4">
            <label for="customDescription" class="block text-sm font-medium text-gray-700">Custom Description</label>
            <textarea id="customDescription" wire:model="customDescription" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
        </div>

        <div class="mb-4">
            <label for="video" class="block text-sm font-medium text-gray-700">Video</label>
            <input type="file" id="video" wire:model="video" accept="video/mp4,video/quicktime" class="mt-1 block w-full">
        </div>

        <div class="flex justify-between">
            <button type="submit" class="btn btn-primary">Submit Property</button>
            <button type="button" wire:click="preview" class="btn btn-secondary">Preview</button>
        </div>
    </form>

    <div class="mt-8">
        @livewire('property-preview-component')
    </div>
</div>
            <input type="text" id="location" wire:model="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
            <input type="number" id="price" wire:model="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="bedrooms" class="block text-sm font-medium text-gray-700">Bedrooms</label>
            <input type="number" id="bedrooms" wire:model="bedrooms" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity