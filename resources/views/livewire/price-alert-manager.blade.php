<div>
    <h3 class="text-lg font-semibold mb-4">Price Alerts</h3>

    <form wire:submit.prevent="createAlert" class="mb-6">
        <div class="mb-4">
            <label for="alertPercentage" class="block text-sm font-medium text-gray-700">Alert Percentage</label>
            <input type="number" id="alertPercentage" wire:model="alertPercentage" step="0.1" min="0.1" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            @error('alertPercentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="alertFrequency" class="block text-sm font-medium text-gray-700">Alert Frequency</label>
            <select id="alertFrequency" wire:model="alertFrequency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>
            @error('alertFrequency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-500 hover: