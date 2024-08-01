<?php

namespace App\Services;

class InvestmentAnalysisService
{
    public function analyze(float $purchasePrice, float $annualRentalIncome, float $annualExpenses, float $appreciationRate, int $holdingPeriod): array
    {
        $netOperatingIncome = $annualRentalIncome - $annualExpenses;
        $capRate = ($netOperatingIncome / $purchasePrice) * 100;
        $cashFlow = $netOperatingIncome;
        $appreciationValue = $purchasePrice * pow(1 + ($appreciationRate / 100), $holdingPeriod);
        $totalProfit = ($appreciationValue - $purchasePrice) + ($cashFlow * $holdingPeriod);
        $roi = ($totalProfit / $purchasePrice) * 100;

        return [
            'cap_rate' => round($capRate, 2),
            'cash_flow' => round($cashFlow, 2),
            'roi' => round($roi, 2),
            'total_profit' => round($totalProfit, 2),
            'future_value' => round($appreciationValue, 2),
        ];
    }
}