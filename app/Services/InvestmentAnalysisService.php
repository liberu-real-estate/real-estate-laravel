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
            'annual_roi' => round($roi / $holdingPeriod, 2),
            'cash_on_cash_return' => round(($cashFlow / $purchasePrice) * 100, 2),
        ];
    }

    public function compareScenarios(array $scenarios): array
    {
        $results = [];
        foreach ($scenarios as $index => $scenario) {
            $results[$index] = $this->analyze(
                $scenario['purchasePrice'],
                $scenario['annualRentalIncome'],
                $scenario['annualExpenses'],
                $scenario['appreciationRate'],
                $scenario['holdingPeriod']
            );
        }

        $bestScenario = $this->findBestScenario($results);

        return [
            'scenarios' => $results,
            'best_scenario' => $bestScenario,
        ];
    }

    private function findBestScenario(array $results): array
    {
        $bestScenario = [
            'index' => 0,
            'roi' => $results[0]['roi'],
        ];

        foreach ($results as $index => $result) {
            if ($result['roi'] > $bestScenario['roi']) {
                $bestScenario['index'] = $index;
                $bestScenario['roi'] = $result['roi'];
            }
        }

        return $bestScenario;
    }
}