<div>
    <h2 class="text-2xl font-bold mb-4">Purchase Insurance</h2>
    <form wire:submit.prevent="getQuote">
        <div class="mb-4">
            <label for="coverageAmount" class="block text-sm font-medium text-gray-700">Coverage Amount</label>
            <input type="number" id="coverageAmount" wire:model="coverageAmount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            @error('coverageAmount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="deductible" class="block text-sm font-medium text-gray-700">Deductible</label>
            <input type="number" id="deductible" wire:model="deductible" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            @error('deductible') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="term" class="block text-sm font-medium text-gray-700">Term (years)</label>
            <select id="term" wire:model="term" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="1">1 year</option>
                <option value="2">2 years</option>
                <option value="3">3 years</option>
                <option value="5">5 years</option>
            </select>
            @error('term') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
            Get Quote
        </button>
    </form>

    @if($quote)
        <div class="mt-6">
            <h3 class="text-xl font-semibold mb-2">Insurance Quote</h3>
            <p>Premium: ${{ number_format($quote['premium'], 2) }}</p>
            <button wire:click="purchasePolicy" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                Purchase Policy
            </button>
        </div>
    @endif
</div>