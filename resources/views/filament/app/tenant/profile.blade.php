<x-filament::page>
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-4">
            Save
        </x-filament::button>
    </form>
</x-filament::page>