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

    /**
     * Calculate Land and Buildings Transaction Tax (LBTT) for Scotland.
     */
    public function calculateLBTT(float $purchasePrice, string $buyerType): array
    {
        $originalPrice = $purchasePrice;
        $lbtt = 0;
        $rates = $this->getLBTTRates($buyerType);

        $previousThreshold = 0;

        foreach ($rates as $threshold => $rate) {
            if ($purchasePrice > $threshold) {
                $taxableAmount = $threshold - $previousThreshold;
                $lbtt += $taxableAmount * $rate;
                $previousThreshold = $threshold;
            } else {
                $taxableAmount = $purchasePrice - $previousThreshold;
                $lbtt += $taxableAmount * $rate;
                break;
            }
        }

        return [
            'lbtt' => round($lbtt, 2),
            'effective_tax_rate' => $originalPrice > 0 ? round(($lbtt / $originalPrice) * 100, 2) : 0,
        ];
    }

    /**
     * Calculate Land Transaction Tax (LTT) for Wales.
     */
    public function calculateLTT(float $purchasePrice, string $buyerType): array
    {
        $originalPrice = $purchasePrice;
        $ltt = 0;
        $rates = $this->getLTTRates($buyerType);

        $previousThreshold = 0;

        foreach ($rates as $threshold => $rate) {
            if ($purchasePrice > $threshold) {
                $taxableAmount = $threshold - $previousThreshold;
                $ltt += $taxableAmount * $rate;
                $previousThreshold = $threshold;
            } else {
                $taxableAmount = $purchasePrice - $previousThreshold;
                $ltt += $taxableAmount * $rate;
                break;
            }
        }

        return [
            'ltt' => round($ltt, 2),
            'effective_tax_rate' => $originalPrice > 0 ? round(($ltt / $originalPrice) * 100, 2) : 0,
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

    /**
     * LBTT rates for Scotland (Land and Buildings Transaction Tax).
     * Residential property rates.
     */
    private function getLBTTRates(string $buyerType): array
    {
        switch ($buyerType) {
            case 'first_time_buyer':
                // First-time buyer relief: 0% up to £175,000
                return [
                    0 => 0,
                    175000 => 0,
                    250000 => 0.05,
                    325000 => 0.05,
                    750000 => 0.10,
                    PHP_INT_MAX => 0.12,
                ];
            case 'home_mover':
                return [
                    0 => 0,
                    145000 => 0,
                    250000 => 0.02,
                    325000 => 0.05,
                    750000 => 0.10,
                    PHP_INT_MAX => 0.12,
                ];
            case 'additional_property':
                // Additional Dwelling Supplement (ADS): 6% surcharge on top of standard rates
                return [
                    0 => 0,
                    145000 => 0.06,
                    250000 => 0.08,
                    325000 => 0.11,
                    750000 => 0.16,
                    PHP_INT_MAX => 0.18,
                ];
            default:
                throw new InvalidArgumentException('Invalid buyer type');
        }
    }

    /**
     * LTT rates for Wales (Land Transaction Tax).
     */
    private function getLTTRates(string $buyerType): array
    {
        switch ($buyerType) {
            case 'first_time_buyer':
            case 'home_mover':
                return [
                    0 => 0,
                    225000 => 0,
                    400000 => 0.06,
                    750000 => 0.075,
                    1500000 => 0.10,
                    PHP_INT_MAX => 0.12,
                ];
            case 'additional_property':
                return [
                    0 => 0,
                    225000 => 0.04,
                    400000 => 0.10,
                    750000 => 0.115,
                    1500000 => 0.14,
                    PHP_INT_MAX => 0.17,
                ];
            default:
                throw new InvalidArgumentException('Invalid buyer type');
        }
    }
}