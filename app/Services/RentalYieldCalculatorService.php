<?php

namespace App\Services;

class RentalYieldCalculatorService
{
    public function calculateRentalYield(float $propertyValue, float $annualRentalIncome, float $annualExpenses = 0): array
    {
        $netAnnualIncome = $annualRentalIncome - $annualExpenses;
        $grossYield = ($annualRentalIncome / $propertyValue) * 100;
        $netYield = ($netAnnualIncome / $propertyValue) * 100;

        return [
            'property_value' => round($propertyValue, 2),
            'annual_rental_income' => round($annualRentalIncome, 2),
            'annual_expenses' => round($annualExpenses, 2),
            'net_annual_income' => round($netAnnualIncome, 2),
            'gross_yield' => round($grossYield, 2),
            'net_yield' => round($netYield, 2),
        ];
    }
}