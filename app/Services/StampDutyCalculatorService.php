<?php

namespace App\Services;

class StampDutyCalculatorService
{
    public function calculateStampDuty(float $purchasePrice, string $buyerType): array
    {
        $stampDuty = 0;
        $rates = $this->getStampDutyRates($buyerType);

        foreach ($rates as $threshold => $rate) {
            if ($purchasePrice > $threshold) {
                $stampDuty += ($purchasePrice - $threshold) * $rate;
                $purchasePrice = $threshold;
            }
        }

        return [
            'stamp_duty' => round($stampDuty, 2),
            'effective_tax_rate' => round(($stampDuty / $purchasePrice) * 100, 2),
        ];
    }

    private function getStampDutyRates(string $buyerType): array
    {
        switch ($buyerType) {
            case 'first_time_buyer':
                return [
                    300000 => 0,
                    500000 => 0.05,
                    925000 => 0.05,
                    1500000 => 0.10,
                    PHP_INT_MAX => 0.12,
                ];
            case 'home_mover':
                return [
                    250000 => 0,
                    925000 => 0.05,
                    1500000 => 0.10,
                    PHP_INT_MAX => 0.12,
                ];
            case 'additional_property':
                return [
                    250000 => 0.03,
                    925000 => 0.08,
                    1500000 => 0.13,
                    PHP_INT_MAX => 0.15,
                ];
            default:
                throw new \InvalidArgumentException('Invalid buyer type');
        }
    }
}