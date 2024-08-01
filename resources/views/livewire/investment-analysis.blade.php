<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-semibold mb-4">Investment Analysis for {{ $property->title }}</h2>

    <form wire:submit.prevent="analyze" class="mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="purchasePrice" class="block mb-2">Purchase Price</label>
                <input type="number" id="purchasePrice" wire:model="purchasePrice" class="w-full p-2 border rounded" step="0.01" required>
            </div>
            <div>
                <label for="annualRentalIncome" class="block mb-2">Annual Rental Income</label>
                <input type="number" id="annualRentalIncome" wire:model="annualRentalIncome" class="w-full p-2 border rounded" step="0.01" required>
            </div>
            <div>
                <label for="annualExpenses" class="block mb-2">Annual Expenses</label>
                <input type="number" id="annualExpenses" wire:model="annualExpenses" class="w-full p-2 border rounded" step="0.01" required>
            </div>
            <div>
                <label for="appreciationRate" class="block mb-2">Appreciation Rate (%)</label>
                <input type="number" id="appreciationRate" wire:model="appreciationRate" class="w-full p-2 border rounded" step="0.1" required>
            </div>
            <div>
                <label for="holdingPeriod" class="block mb-2">Holding Period (years)</label>
                <input type="number" id="holdingPeriod" wire:model="holdingPeriod" class="w-full p-2 border rounded" step="1" required>
            </div>
        </div>
        <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Analyze Investment</button>
    </form>

    @if($analysisResult)
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-semibold mb-4">Analysis Results</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <p class="font-semibold">Cap Rate</p>
                <p class="text-2xl">{{ $analysisResult['cap_rate'] }}%</p>
            </div>
            <div>
                <p class="font-semibold">Annual Cash Flow</p>
                <p class="text-2xl">{{ \App\Helpers\SiteSettingsHelper::getCurrency() }}{{ number_format($analysisResult['cash_flow'], 2) }}</p>
            </div>
            <div>
                <p class="font-semibold">ROI</p>
                <p class="text-2xl">{{ $analysisResult['roi'] }}%</p>
            </div>
            <div>
                <p class="font-semibold">Total Profit</p>
                <p class="text-2xl">{{ \App\Helpers\SiteSettingsHelper::getCurrency() }}{{ number_format($analysisResult['total_profit'], 2) }}</p>
            </div>
            <div>
                <p class="font-semibold">Future Value</p>
                <p class="text-2xl">{{ \App\Helpers\SiteSettingsHelper::getCurrency() }}{{ number_format($analysisResult['future_value'], 2) }}</p>
            </div>
        </div>
        <div class="mt-8">
            <canvas id="investmentChart" width="400" height="200"></canvas>
        </div>
    </div>
    @endif

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('analysisComplete', function (data) {
                const ctx = document.getElementById('investmentChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Purchase Price', 'Future Value', 'Total Profit'],
                        datasets: [{
                            label: 'Investment Analysis',
                            data: [data.purchasePrice, data.future_value, data.total_profit],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(75, 192, 192, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(75, 192, 192, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
        });
    </script>
    @endpush
</div>