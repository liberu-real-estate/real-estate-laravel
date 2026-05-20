<x-filament-panels::page>
    <x-filament::grid columns="3">
        <x-filament::grid.column>
            <x-filament::card>
                <h2 class="text-lg font-semibold">Total Properties</h2>
                <p class="text-3xl font-bold">{{ $this->totalProperties }}</p>
            </x-filament::card>
        </x-filament::grid.column>

        <x-filament::grid.column>
            <x-filament::card>
                <h2 class="text-lg font-semibold">Occupied Properties</h2>
                <p class="text-3xl font-bold">{{ $this->occupiedProperties }}</p>
            </x-filament::card>
        </x-filament::grid.column>

        <x-filament::grid.column>
            <x-filament::card>
                <h2 class="text-lg font-semibold">Total Tenants</h2>
                <p class="text-3xl font-bold">{{ $this->totalTenants }}</p>
            </x-filament::card>
        </x-filament::grid.column>
    </x-filament::grid>

    <x-filament::card class="mt-6">
        <h2 class="text-lg font-semibold mb-4">Landlord Recent Activity</h2>
        <!-- Add landlord-specific recent activity content here -->
    </x-filament::card>
</x-filament-panels::page>