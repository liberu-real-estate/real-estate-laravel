<?php

namespace App\Filament\Staff\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Property;
use App\Models\Transaction;
use Carbon\Carbon;

class TopPerformingProperties extends ChartWidget
{
    protected static ?string $heading = 'Top Performing Properties';

    protected function getData(): array
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $topProperties = Property::withSum(['transactions' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('transaction_date', [$startDate, $endDate]);
        }], 'transaction_amount')
            ->orderByDesc('transactions_sum_transaction_amount')
            ->take(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $topProperties->pluck('transactions_sum_transaction_amount')->toArray(),
                ],
            ],
            'labels' => $topProperties->pluck('title')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}