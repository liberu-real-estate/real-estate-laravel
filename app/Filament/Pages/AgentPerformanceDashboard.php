<?php

namespace App\Filament\Pages;

use App\Models\Agent;
use App\Models\AgentPerformanceMetrics;
use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class AgentPerformanceDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.agent-performance-dashboard';

    public function getHeaderWidgets(): array
    {
        return [
            AgentPerformanceOverview::class,
        ];
    }

    public function getFooterWidgets(): array
    {
        return [
            TopPerformingAgentsWidget::class,
            SalesVolumeChartWidget::class,
        ];
    }
}

class AgentPerformanceOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $totalSales = AgentPerformanceMetrics::sum('sales_volume');
        $averageRating = AgentPerformanceMetrics::avg('customer_satisfaction_rating');
        $totalTransactions = AgentPerformanceMetrics::sum('number_of_transactions');

        return [
            Card::make('Total Sales', '$' . number_format($totalSales, 2))
                ->description('Across all agents')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('success'),
            Card::make('Average Customer Satisfaction', number_format($averageRating, 2))
                ->description('Out of 5')
                ->descriptionIcon('heroicon-s-star')
                ->color('warning'),
            Card::make('Total Transactions', $totalTransactions)
                ->description('Completed deals')
                ->descriptionIcon('heroicon-s-shopping-cart')
                ->color('primary'),
        ];
    }
}