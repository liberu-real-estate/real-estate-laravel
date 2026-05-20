<div class="p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">AI Property Management Assistant</h2>

    <div class="mb-4">
        <label for="context" class="block text-sm font-medium text-gray-700">Context</label>
        <select wire:model="context" id="context" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option value="general">General</option>
            <option value="maintenance">Maintenance</option>
            <option value="financial">Financial</option>
            <option value="tenant">Tenant Communication</option>
        </select>
    </div>

    @if($context === 'maintenance')
        <div class="mb-4">
            <label for="selectedMaintenanceRequest" class="block text-sm font-medium text-gray-700">Maintenance Request</label>
            <select wire:model="selectedMaintenanceRequest" id="selectedMaintenanceRequest" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Select a maintenance request</option>
                @foreach($maintenanceRequests as $request)
                    <option value="{{ $request->id }}">{{ $request->property->address }} - {{ $request->description }}</option>
                @endforeach
            </select>
        </div>
        <button wire:click="scheduleMaintenance" class="mb-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Schedule Maintenance
        </button>
    @elseif($context === 'financial')
        <div class="mb-4">
            <label for="selectedProperty" class="block text-sm font-medium text-gray-700">Property</label>
            <select wire:model="selectedProperty" id="selectedProperty" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Select a property</option>
                @foreach($properties as $property)
                    <option value="{{ $property->id }}">{{ $property->address }}</option>
                @endforeach
            </select>
        </div>
        <button wire:click="generateFinancialReport" class="mb-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Generate Financial Report
        </button>
    @elseif($context === 'tenant')
        <div class="mb-4">
            <label for="selectedTenant" class="block text-sm font-medium text-gray-700">Tenant</label>
            <select wire:model="selectedTenant" id="selectedTenant" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Select a tenant</option>
                @foreach($tenants as $tenant)
                    <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="mb-4">
        <label for="input" class="block text-sm font-medium text-gray-700">Your Question or Request</label>
        <textarea wire:model="input" id="input" rows="3" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
    </div>

    <button wire:click="generateResponse" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        Get AI Response
    </button>

    @if($response)
        <div class="mt-4 p-4 bg-gray-100 rounded-md">
            <h3 class="text-lg font-medium text-gray-900 mb-2">AI Response:</h3>
            <p class="text-gray-700">{{ $response }}</p>
        </div>
    @endif
</div>