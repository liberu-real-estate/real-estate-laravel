<div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($payments as $payment)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $payment->payment_date->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${{ number_format($payment->amount, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($payment->status) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</div>