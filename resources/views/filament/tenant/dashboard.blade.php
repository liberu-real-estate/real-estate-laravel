<x-filament-panels::page>
    <x-filament::grid columns="3">
        <x-filament::grid.column>
            <x-filament::card>
                <h2 class="text-lg font-semibold">Current Property</h2>
                <p class="text-3xl font-bold">{{ $this->currentProperty->address ?? 'N/A' }}</p>
            </x-filament::card>
        </x-filament::grid.column>

        <x-filament::grid.column>
            <x-filament::card>
                <h2 class="text-lg font-semibold">Next Rent Due</h2>
                <p class="text-3xl font-bold">{{ $this->rentDueDate ? $this->rentDueDate->format('M d, Y') : 'N/A' }}</p>
            </x-filament::card>
        </x-filament::grid.column>

        <x-filament::grid.column>
            <x-filament::card>
                <h2 class="text-lg font-semibold">Open Maintenance Requests</h2>
                <p class="text-3xl font-bold">{{ $this->openMaintenanceRequests }}</p>
            </x-filament::card>
        </x-filament::grid.column>
    </x-filament::grid>

    <x-filament::card class="mt-6">
        <h2 class="text-lg font-semibold mb-4">Tenant Recent Activity</h2>
        <!-- Add tenant-specific recent activity content here -->
    </x-filament::card>
</x-filament-panels::page>