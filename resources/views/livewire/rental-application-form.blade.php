<div>
    <h2 class="text-2xl font-semibold mb-4">Rental Application for {{ $property->title }}</h2>
    <form wire:submit.prevent="submit">
        <div class="mb-4">
            <label for="employment_status" class="block text-sm font-medium text-gray-700">Employment Status</label>
            <input type="text" id="employment_status" wire:model="employment_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            @error('employment_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="annual_income" class="block text-sm font-medium text-gray-700">Annual Income</label>
            <input type="number" id="annual_income" wire:model="annual_income" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            @error('annual_income') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
            Submit Application
        </button>
    </form>
</div>