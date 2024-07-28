<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Property Calculators</h1>

    <div class="mb-6">
        <button wire:click="setCalculatorType('mortgage')" class="px-4 py-2 mr-2 {{ $calculatorType === 'mortgage' ? 'bg-blue-500 text-white' : 'bg-gray-200' }} rounded">Mortgage Calculator</button>
        <button wire:click="setCalculatorType('cost_of_moving')" class="px-4 py-2 mr-2 {{ $calculatorType === 'cost_of_moving' ? 'bg-blue-500 text-white' : 'bg-gray-200' }} rounded">Cost of Moving Calculator</button>
        <button wire:click="setCalculatorType('stamp_duty')" class="px-4 py-2 {{ $calculatorType === 'stamp_duty' ? 'bg-blue-500 text-white' : 'bg-gray-200' }} rounded">Stamp Duty Calculator</button>
    </div>

    @if ($calculatorType === 'mortgage')
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-2xl font-bold mb-4">Mortgage Calculator</h2>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="propertyPrice">
                    Property Price (£)
                </label>
                <input wire:model="propertyPrice" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="propertyPrice" type="number" placeholder="Enter property price">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="loanAmount">
                    Loan Amount (£)
                </label>
                <input wire:model="loanAmount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="loanAmount" type="number" placeholder="Enter loan amount">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="interestRate">
                    Interest Rate (%)
                </label>
                <input wire:model="interestRate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="interestRate" type="number" step="0.01" placeholder="Enter interest rate">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="loanTerm">
                    Loan Term (years)
                </label>
                <input wire:model="loanTerm" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="loanTerm" type="number" placeholder="Enter loan term">
            </div>
            <div class="flex items-center justify-between">
                <button wire:click="calculateMortgage" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                    Calculate
                </button>
            </div>
        </div>

        @if ($mortgageResult)
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-4" role="alert">
                <p class="font-bold">Mortgage Calculation Results:</p>
                <p>Monthly Payment: £{{ number_format($mortgageResult['monthly_payment'], 2) }}</p>
                <p>Total Payment: £{{ number_format($mortgageResult['total_payment'], 2) }}</p>
                <p>Total Interest: £{{ number_format($mortgageResult['total_interest'], 2) }}</p>
            </div>
        @endif

    @elseif ($calculatorType === 'cost_of_moving')
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-2xl font-bold mb-4">Cost of Moving Calculator</h2>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="propertyValue">
                    Property Value (£)
                </label>