<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\MarketAnalysisService;

class MarketAnalysisChart extends Component
{
    public $startDate;
    public $endDate;
    public $propertyIds;
    public $chartData;

    public function mount($startDate, $endDate, $propertyIds = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->propertyIds = $propertyIds;
        $this->updateChartData();
    }

    public function updateChartData()
    {
        $marketAnalysisService = app(MarketAnalysisService::class);
        $trends = $marketAnalysisService->getMarketTrends($this->startDate, $this->endDate, $this->propertyIds);

        // $this->chartData = [
        //     'labels' => $trends->pluck('month')->unique()->values()->toArray(),
        //     'datasets' => [
        //         [
        //             'label
    }
}