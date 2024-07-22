<x-filament-panels::page>
    <x-filament::grid columns="3">
        <x-filament::grid.column>
            <x-filament::card>
                <h2 class="text-lg font-semibold">My Properties</h2>
                <p class="text-3xl font-bold">{{ $this->myProperties }}</p>
            </x-filament::card>
        </x-filament::grid.column>

        <x-filament::grid.column>
            <x-filament::card>
                <h2 class="text-lg font-semibold">Active Listings</h2>
                <p class="text-3xl font-bold">{{ $this->activeListings }}</p>
            </x-filament::card>
        </x-filament::grid.column>

        <x-filament::grid.column>
            <x-filament::card>
                <h2 class="text-lg font-semibold">Total Bookings</h2>
                <p class="text-3xl font-bold">{{ $this->totalBookings }}</p>
            </x-filament::card>
        </x-filament::grid.column>
    </x-filament::grid>

    <x-filament::card class="mt-6">
        <h2 class="text-lg font-semibold mb-4">Seller Recent Activity</h2>
        <!-- Add seller-specific recent activity content here -->
    </x-filament::card>
</x-filament-panels::page>