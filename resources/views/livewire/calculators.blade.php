
<div class="container mx-auto px-4 py-8" x-data="{ calculatorType: 'mortgage' }">
    <h1 class="text-3xl font-bold mb-6">Property Calculators</h1>

    <div class="mb-6">
        <button x-on:click="calculatorType = 'mortgage'" class="px-4 py-2 mr-2 mb-2" x-bind:class="calculatorType === 'mortgage' ? 'bg-blue-500 text-white' : 'bg-gray-200'">Mortgage Calculator</button>
        <button x-on:click="calculatorType = 'costOfMoving'" class="px-4 py-2 mr-2 mb-2" x-bind:class="calculatorType === 'costOfMoving' ? 'bg-blue-500 text-white' : 'bg-gray-200'">Cost of Moving Calculator</button>
        <button x-on:click="calculatorType = 'stampDuty'" class="px-4 py-2 mr-2 mb-2" x-bind:class="calculatorType === 'stampDuty' ? 'bg-blue-500 text-white' : 'bg-gray-200'">Stamp Duty Calculator</button>
        <button x-on:click="calculatorType = 'homeValuation'" class="px-4 py-2 mb-2" x-bind:class="calculatorType === 'homeValuation' ? 'bg-blue-500 text-white' : 'bg-gray-200'">Home Valuation</button>
    </div>

    <div x-show="calculatorType === 'mortgage'" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
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
        <div x-show="calculatorType === 'mortgage'" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-4" role="alert">
            <p class="font-bold">Mortgage Calculation Results:</p>
            <p>Monthly Payment: £{{ number_format($mortgageResult['monthly_payment'], 2) }}</p>
            <p>Total Payment: £{{ number_format($mortgageResult['total_payment'], 2) }}</p>
            <p>Total Interest: £{{ number_format($mortgageResult['total_interest'], 2) }}</p>
        </div>
    @endif

    <div x-show="calculatorType === 'costOfMoving'" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
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
        <div x-show="calculatorType === 'costOfMoving'" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-4" role="alert">
            <p class="font-bold">Cost of Moving Calculation Results:</p>
            <p>Estate Agent Fee: £{{ number_format($costOfMovingResult['estate_agent_fee'], 2) }}</p>
            <p>Conveyancing Fee: £{{ number_format($costOfMovingResult['conveyancing_fee'], 2) }}</p>
            <p>Survey Fee: £{{ number_format($costOfMovingResult['survey_fee'], 2) }}</p>
            <p>Removals: £{{ number_format($costOfMovingResult['removals'], 2) }}</p>
            <p>Energy Performance Certificate: £{{ number_format($costOfMovingResult['energy_performance_certificate'], 2) }}</p>
            <p>Total Cost: £{{ number_format($costOfMovingResult['total_cost'], 2) }}</p>
        </div>
    @endif

    <div x-show="calculatorType === 'stampDuty'" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
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
        <div x-show="calculatorType === 'stampDuty'" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-4" role="alert">
            <p class="font-bold">Stamp Duty Calculation Results:</p>
            <p>Stamp Duty: £{{ number_format($stampDutyResult['stamp_duty'], 2) }}</p>
            <p>Effective Tax Rate: {{ number_format($stampDutyResult['effective_tax_rate'], 2) }}%</p>
        </div>
    @endif

    <div x-show="calculatorType === 'homeValuation'" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-2xl font-bold mb-4">Home Valuation Tool</h2>
        <p class="text-gray-600 mb-4">Enter your property details to get an estimated value</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="valuationPropertySize">
                    Property Size (sq ft)
                </label>
                <input wire:model="valuationPropertySize" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="valuationPropertySize" type="number" placeholder="e.g., 1500">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="valuationBedrooms">
                    Number of Bedrooms
                </label>
                <input wire:model="valuationBedrooms" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="valuationBedrooms" type="number" placeholder="e.g., 3">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="valuationBathrooms">
                    Number of Bathrooms
                </label>
                <input wire:model="valuationBathrooms" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="valuationBathrooms" type="number" placeholder="e.g., 2">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="valuationYearBuilt">
                    Year Built
                </label>
                <input wire:model="valuationYearBuilt" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="valuationYearBuilt" type="number" placeholder="e.g., 2010">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="valuationPropertyType">
                    Property Type
                </label>
                <select wire:model="valuationPropertyType" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="valuationPropertyType">
                    <option value="detached">Detached</option>
                    <option value="semi-detached">Semi-Detached</option>
                    <option value="terraced">Terraced</option>
                    <option value="apartment">Apartment</option>
                    <option value="bungalow">Bungalow</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="valuationCondition">
                    Property Condition
                </label>
                <select wire:model="valuationCondition" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="valuationCondition">
                    <option value="excellent">Excellent</option>
                    <option value="good">Good</option>
                    <option value="fair">Fair</option>
                    <option value="poor">Poor</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="valuationLocation">
                    Location Quality
                </label>
                <select wire:model="valuationLocation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="valuationLocation">
                    <option value="prime">Prime</option>
                    <option value="good">Good</option>
                    <option value="average">Average</option>
                    <option value="below-average">Below Average</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="valuationBasePrice">
                    Base Price per sq ft (£)
                </label>
                <input wire:model="valuationBasePrice" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="valuationBasePrice" type="number" placeholder="e.g., 3000">
                <p class="text-xs text-gray-500 mt-1">Average price per square foot in your area</p>
            </div>
        </div>
        
        <div class="flex items-center justify-between mt-6">
            <button wire:click="calculateHomeValuation" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                Get Home Valuation
            </button>
        </div>
    </div>

    @if ($homeValuationResult)
        <div x-show="calculatorType === 'homeValuation'" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mt-4">
            <h3 class="text-xl font-bold mb-4 text-blue-600">Home Valuation Results</h3>
            
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                <p class="font-bold text-2xl text-blue-800">Estimated Value: £{{ number_format($homeValuationResult['estimated_value'], 2) }}</p>
                <p class="text-sm text-gray-600 mt-2">Value Range: £{{ number_format($homeValuationResult['min_value'], 2) }} - £{{ number_format($homeValuationResult['max_value'], 2) }}</p>
                <p class="text-sm text-gray-600">Confidence Level: {{ $homeValuationResult['confidence_level'] }}%</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="bg-gray-50 p-3 rounded">
                    <p class="text-sm font-semibold text-gray-700">Property Details</p>
                    <p class="text-sm text-gray-600">Size: {{ number_format($homeValuationResult['property_size']) }} sq ft</p>
                    <p class="text-sm text-gray-600">Bedrooms: {{ $homeValuationResult['bedrooms'] }}</p>
                    <p class="text-sm text-gray-600">Bathrooms: {{ $homeValuationResult['bathrooms'] }}</p>
                    <p class="text-sm text-gray-600">Year Built: {{ $homeValuationResult['year_built'] }} ({{ $homeValuationResult['property_age'] }} years old)</p>
                </div>
                
                <div class="bg-gray-50 p-3 rounded">
                    <p class="text-sm font-semibold text-gray-700">Property Characteristics</p>
                    <p class="text-sm text-gray-600">Type: {{ ucfirst($homeValuationResult['property_type']) }}</p>
                    <p class="text-sm text-gray-600">Condition: {{ ucfirst($homeValuationResult['condition']) }}</p>
                    <p class="text-sm text-gray-600">Location: {{ ucfirst($homeValuationResult['location']) }}</p>
                </div>
            </div>
            
            <div class="bg-gray-50 p-4 rounded">
                <p class="text-sm font-semibold text-gray-700 mb-2">Valuation Breakdown</p>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <p class="text-gray-600">Base Value:</p>
                    <p class="text-gray-800 font-medium">£{{ number_format($homeValuationResult['breakdown']['base_value'], 2) }}</p>
                    
                    <p class="text-gray-600">Type Multiplier:</p>
                    <p class="text-gray-800 font-medium">{{ $homeValuationResult['breakdown']['type_multiplier'] }}x</p>
                    
                    <p class="text-gray-600">Condition Multiplier:</p>
                    <p class="text-gray-800 font-medium">{{ $homeValuationResult['breakdown']['condition_multiplier'] }}x</p>
                    
                    <p class="text-gray-600">Location Multiplier:</p>
                    <p class="text-gray-800 font-medium">{{ $homeValuationResult['breakdown']['location_multiplier'] }}x</p>
                    
                    <p class="text-gray-600">Age Adjustment:</p>
                    <p class="text-gray-800 font-medium">{{ $homeValuationResult['breakdown']['age_adjustment'] }}x</p>
                    
                    <p class="text-gray-600">Room Bonus:</p>
                    <p class="text-gray-800 font-medium">£{{ number_format($homeValuationResult['breakdown']['room_bonus'], 2) }}</p>
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-yellow-50 border-l-4 border-yellow-400">
                <p class="text-xs text-gray-700">
                    <strong>Note:</strong> This is an automated estimate based on the information provided. 
                    For a precise valuation, please contact a professional surveyor or estate agent.
                </p>
            </div>
        </div>
    @endif
</div>