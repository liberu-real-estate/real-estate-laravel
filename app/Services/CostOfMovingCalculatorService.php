<?php

namespace App\Services;

class CostOfMovingCalculatorService
{
    public function calculateCostOfMoving(float $propertyValue, bool $isFirstTimeBuyer, float $movingDistance): array
    {
        $estateAgentFee = $this->calculateEstateAgentFee($propertyValue);
        $conveyancingFee = $this->calculateConveyancingFee($isFirstTimeBuyer);
        $surveyFee = $this->calculateSurveyFee($propertyValue);
        $removals = $this->calculateRemovals($movingDistance);
        $energyPerformanceCertificate = 120; // Fixed cost

        $totalCost = $estateAgentFee + $conveyancingFee + $surveyFee + $removals + $energyPerformanceCertificate;

        return [
            'estate_agent_fee' => round($estateAgentFee, 2),
            'conveyancing_fee' => $conveyancingFee,
            'survey_fee' => $surveyFee,
            'removals' => $removals,
            'energy_performance_certificate' => $energyPerformanceCertificate,
            'total_cost' => round($totalCost, 2),
        ];
    }

    private function calculateEstateAgentFee(float $propertyValue): float
    {
        return $propertyValue * 0.015; // Assuming 1.5% of property value
    }

    private function calculateConveyancingFee(bool $isFirstTimeBuyer): int
    {
        return $isFirstTimeBuyer ? 800 : 1200;
    }

    private function calculateSurveyFee(float $propertyValue): int
    {
        if ($propertyValue < 100000) {
            return 300;
        } elseif ($propertyValue < 250000) {
            return 400;
        } else {
            return 500;
        }
    }

    private function calculateRemovals(float $movingDistance): int
    {
        $baseCost = 600;
        $distanceCost = $movingDistance * 0.5; // £0.50 per mile
        return round($baseCost + $distanceCost);
    }
}