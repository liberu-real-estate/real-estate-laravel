<?php

namespace App\Services;

class MortgageCalculatorService
{
    public function calculateMortgage(float $propertyPrice, float $loanAmount, float $interestRate, int $loanTerm): array
    {
        $numberOfPayments = $loanTerm * 12;

        if ($interestRate == 0) {
            $monthlyPayment = $numberOfPayments > 0 ? $loanAmount / $numberOfPayments : 0;
        } else {
            $monthlyInterestRate = $interestRate / 100 / 12;
            $monthlyPayment = $loanAmount * ($monthlyInterestRate * pow(1 + $monthlyInterestRate, $numberOfPayments)) / (pow(1 + $monthlyInterestRate, $numberOfPayments) - 1);
        }

        $amortizationSchedule = $this->generateAmortizationSchedule($loanAmount, $interestRate > 0 ? $interestRate / 100 / 12 : 0, $numberOfPayments, $monthlyPayment);

        return [
            'monthly_payment' => round($monthlyPayment, 2),
            'total_payment' => round($monthlyPayment * $numberOfPayments, 2),
            'total_interest' => round(($monthlyPayment * $numberOfPayments) - $loanAmount, 2),
            'amortization_schedule' => $amortizationSchedule,
        ];
    }

    private function generateAmortizationSchedule(float $loanAmount, float $monthlyInterestRate, int $numberOfPayments, float $monthlyPayment): array
    {
        $schedule = [];
        $remainingBalance = $loanAmount;

        for ($month = 1; $month <= $numberOfPayments; $month++) {
            $interestPayment = $remainingBalance * $monthlyInterestRate;
            $principalPayment = $monthlyPayment - $interestPayment;
            $remainingBalance -= $principalPayment;

            $schedule[] = [
                'month' => $month,
                'payment' => round($monthlyPayment, 2),
                'principal' => round($principalPayment, 2),
                'interest' => round($interestPayment, 2),
                'balance' => round($remainingBalance, 2),
            ];

            if ($remainingBalance <= 0) {
                break;
            }
        }

        return $schedule;
    }
}