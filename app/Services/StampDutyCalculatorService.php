<?php

namespace App\Services;

use InvalidArgumentException;

class StampDutyCalculatorService
{
    public function calculateStampDuty(float $purchasePrice, string $buyerType): array
    {
        $originalPrice = $purchasePrice;
        $stampDuty = 0;
        $rates = $this->getStampDutyRates($buyerType);
        
        $previousThreshold = 0;
        
        foreach ($rates as $threshold => $rate) {
            if ($purchasePrice > $threshold) {
                // Calculate tax on the band between previous threshold and current threshold
                $taxableAmount = $threshold - $previousThreshold;
                $stampDuty += $taxableAmount * $rate;
                $previousThreshold = $threshold;
            } else {
                // Calculate tax on the remaining amount
                $taxableAmount = $purchasePrice - $previousThreshold;
                $stampDuty += $taxableAmount * $rate;
                break;
            }
        }

        return [
            'stamp_duty' => round($stampDuty, 2),
            'effective_tax_rate' => $originalPrice > 0 ? round(($stampDuty / $originalPrice) * 100, 2) : 0,
        ];
    }

    private function getStampDutyRates(string $buyerType): array
    {
        switch ($buyerType) {
            case 'first_time_buyer':
                return [
                    0 => 0,
                    300000 => 0,
                    500000 => 0.05,
                    925000 => 0.05,
                    1500000 => 0.10,
                    PHP_INT_MAX => 0.12,
                ];
            case 'home_mover':
                return [
                    0 => 0,
                    250000 => 0,
                    925000 => 0.05,
                    1500000 => 0.10,
                    PHP_INT_MAX => 0.12,
                ];
            case 'additional_property':
                return [
                    0 => 0.03,
                    250000 => 0.03,
                    925000 => 0.08,
                    1500000 => 0.13,
                    PHP_INT_MAX => 0.15,
                ];
            default:
                throw new InvalidArgumentException('Invalid buyer type');
        }
    }
}