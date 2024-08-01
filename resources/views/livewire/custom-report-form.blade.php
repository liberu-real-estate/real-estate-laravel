<div class="p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Generate Custom Report</h2>

    <form wire:submit.prevent="generateReport">
        <div class="mb-4">
            <label for="reportType" class="block text-sm font-medium text-gray-700">Report Type</label>
            <select wire:model="reportType" id="reportType" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Select a report type</option>
                <option value="financial">Financial Report</option>
                <option value="occupancy">Occupancy Report</option>
                <option value="maintenance">Maintenance Report</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" wire:model="startDate" id="startDate" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-4">
            <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
            <input type="date" wire:model="endDate" id="endDate" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-4">
            <label for="properties" class="block text-sm font-medium text-gray-700">Properties</label>
            <select wire:model="selectedProperties" id="properties" multiple class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @foreach($properties as $property)
                    <option value="{{ $property->id }}">{{ $property->address }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="tenants" class="block text-sm font-medium text-gray-700">Tenants</label>
            <select wire:model="selectedTenants" id="tenants" multiple class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @foreach($tenants as $tenant)
                    <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-between">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Generate Report
            </button>
            <div>
                <button type="button" wire:click="$emit('exportReportToPdf')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Export to PDF
                </button>
                <button type="button" wire:click="$emit('exportReportToExcel')" class="ml-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Export to Excel
                </button>
            </div>
        </div>
    </form>
</div>