<div>
@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Property Calculators</h1>

    <div class="mb-6">
        <button wire:click="setCalculatorType('mortgage')" class="px-4 py-2 mr-2 {{ $calculatorType === 'mortgage' ? 'bg-blue-500 text-white' : 'bg-gray-200' }} rounded">Mortgage Calculator</button>
        <button wire:click="setCalculatorType('costOfMoving')" class="px-4 py-2 mr-2 {{ $calculatorType === 'costOfMoving' ? 'bg-blue-500 text-white' : 'bg-gray-200' }} rounded">Cost of Moving Calculator</button>
        <button wire:click="setCalculatorType('stampDuty')" class="px-4 py-2 mr-2 {{ $calculatorType === 'stampDuty' ? 'bg-blue-500 text-white' : 'bg-gray-200' }} rounded">Stamp Duty Calculator</button>
        <button wire:click="setCalculatorType('rentalYield')" class="px-4 py-2 mr-2 {{ $calculatorType === 'rentalYield' ? 'bg-blue-500 text-white' : 'bg-gray-200' }} rounded">Rental Yield Calculator</button>
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

    @elseif ($calculatorType === 'rentalYield')
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-2xl font-bold mb-4">Rental Yield Calculator</h2>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="rentalPropertyValue">
                    Property Value (£)
                </label>
                <input wire:model="rentalPropertyValue" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="rentalPropertyValue" type="number" placeholder="Enter property value">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="annualRentalIncome">
                    Annual Rental Income (£)
                </label>
                <input wire:model="annualRentalIncome" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="annualRentalIncome" type="number" placeholder="Enter annual rental income">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="annualExpenses">
                    Annual Expenses (£)
                </label>
                <input wire:model="annualExpenses" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="annualExpenses" type="number" placeholder="Enter annual expenses">
            </div>
            <div class="flex items-center justify-between">
                <button wire:click="calculateRentalYield" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                    Calculate
                </button>
            </div>
        </div>

        @if ($rentalYieldResult)
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-4" role="alert">
                <p class="font-bold">Rental Yield Calculation Results:</p>
                <p>Gross Yield: {{ number_format($rentalYieldResult['gross_yield'], 2) }}%</p>
                <p>Net Yield: {{ number_format($rentalYieldResult['net_yield'], 2) }}%</p>
                <p>Annual Return: £{{ number_format($rentalYieldResult['net_annual_income'], 2) }}</p>
            </div>
        @endif

    @elseif ($calculatorType === 'cost_of_moving')
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-2xl font-bold mb-4">Cost of Moving Calculator</h2>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="propertyValue">
                    Property Value (£)
                </label>
                <input wire:model="propertyValue" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="propertyValue" type="number" placeholder="Enter property value">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="isFirstTimeBuyer">
                    First Time Buyer?
                </label>
                <select wire:model="isFirstTimeBuyer" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="isFirstTimeBuyer">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="movingDistance">
                    Moving Distance (miles)
                </label>
                <input wire:model="movingDistance" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="movingDistance" type="number" placeholder="Enter moving distance">
            </div>
            <div class="flex items-center justify-between">
                <button wire:click="calculateCostOfMoving" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                    Calculate
                </button>
            </div>
        </div>

        @if ($costOfMovingResult)
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-4" role="alert">
                <p class="font-bold">Cost of Moving Calculation Results:</p>
                <p>Estate Agent Fee: £{{ number_format($costOfMovingResult['estate_agent_fee'], 2) }}</p>
                <p>Conveyancing Fee: £{{ number_format($costOfMovingResult['conveyancing_fee'], 2) }}</p>
                <p>Survey Fee: £{{ number_format($costOfMovingResult['survey_fee'], 2) }}</p>
                <p>Removals: £{{ number_format($costOfMovingResult['removals'], 2) }}</p>
                <p>Energy Performance Certificate: £{{ number_format($costOfMovingResult['energy_performance_certificate'], 2) }}</p>
                <p>Total Cost: £{{ number_format($costOfMovingResult['total_cost'], 2) }}</p>
            </div>
        @endif
    @elseif ($calculatorType === 'stamp_duty')
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-2xl font-bold mb-4">Stamp Duty Calculator</h2>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="propertyValue">
                    Property Value (£)
                </label>
                <input wire:model="propertyValue" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="propertyValue" type="number" placeholder="Enter property value">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="isFirstTimeBuyer">
                    First Time Buyer?
                </label>
                <select wire:model="isFirstTimeBuyer" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="isFirstTimeBuyer">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="flex items-center justify-between">
                <button wire:click="calculateStampDuty" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                    Calculate
                </button>
            </div>
        </div>

        @if ($stampDutyResult)
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-4" role="alert">
                <p class="font-bold">Stamp Duty Calculation Results:</p>
                <p>Stamp Duty: £{{ number_format($stampDutyResult['stamp_duty'], 2) }}</p>
                <p>Effective Tax Rate: {{ number_format($stampDutyResult['effective_tax_rate'], 2) }}%</p>
            </div>
        @endif
    @endif
</div>
@endsection
</div>
