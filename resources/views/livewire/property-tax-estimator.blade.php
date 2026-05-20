<div class="property-tax-estimator">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
            Property Tax Estimator
        </h3>
        
        @if(!$showResults)
            <div class="space-y-4">
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                    Estimate your property taxes and additional costs based on the purchase price of 
                    <strong>{{ app(\App\Settings\GeneralSettings::class)->site_currency }} {{ number_format($property->price, 2) }}</strong>
                    @if($country === 'UK' || $country === 'GB')
                        in the United Kingdom
                    @endif
                </p>

                @if($country === 'UK' || $country === 'GB')
                    <div>
                        <label for="buyerType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Buyer Type <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="buyerType" id="buyerType" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="first_time_buyer">First Time Buyer</option>
                            <option value="home_mover">Home Mover</option>
                            <option value="additional_property">Additional Property / Buy-to-Let</option>
                        </select>
                        @error('buyerType') 
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        
                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            @if($buyerType === 'first_time_buyer')
                                <p>First-time buyers may benefit from reduced stamp duty rates on properties up to £500,000.</p>
                            @elseif($buyerType === 'home_mover')
                                <p>Standard stamp duty rates apply for those moving home.</p>
                            @elseif($buyerType === 'additional_property')
                                <p>Additional 3% surcharge applies to second homes and buy-to-let properties.</p>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="flex space-x-3">
                    <button wire:click="calculateTax" 
                            class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition">
                        Calculate Estimated Taxes
                    </button>
                </div>
            </div>
        @else
            <div class="space-y-4">
                <!-- Results Header -->
                <div class="bg-primary-50 dark:bg-primary-900 p-4 rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                        Tax Estimation Results
                    </h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Country: <strong>{{ $estimatedTax['country'] }}</strong>
                    </p>
                </div>

                <!-- Breakdown Table -->
                <div class="overflow-hidden border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Item
                                </th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Amount
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($estimatedTax['breakdown'] as $label => $amount)
                                <tr class="{{ $label === 'Total Cost' || str_contains($label, 'Total') ? 'font-semibold bg-gray-50 dark:bg-gray-700' : '' }}">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $label }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-gray-100">
                                        {{ app(\App\Settings\GeneralSettings::class)->site_currency }} {{ number_format($amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Key Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Total Tax</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ app(\App\Settings\GeneralSettings::class)->site_currency }} {{ number_format($estimatedTax['total_tax'], 2) }}
                        </p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Effective Tax Rate</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $estimatedTax['effective_tax_rate'] }}%
                        </p>
                    </div>
                </div>

                @if($country === 'UK' || $country === 'GB')
                    <div class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                    <strong>Note:</strong> These are estimates only. Actual costs may vary. 
                                    Please consult with a solicitor or tax advisor for accurate calculations.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex space-x-3 mt-4">
                    <button wire:click="resetCalculation" 
                            class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-medium py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        Calculate Again
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
