<?php

namespace App\Services;

use InvalidArgumentException;

class PropertyTaxEstimatorService
{
    protected StampDutyCalculatorService $stampDutyCalculator;

    public function __construct(StampDutyCalculatorService $stampDutyCalculator)
    {
        $this->stampDutyCalculator = $stampDutyCalculator;
    }

    /**
     * Estimate property taxes based on country and property details
     *
     * @param float $purchasePrice
     * @param string $country
     * @param array $options (buyer_type, is_first_time_buyer, etc.)
     * @return array
     */
    public function estimatePropertyTax(float $purchasePrice, string $country = 'UK', array $options = []): array
    {
        $country = strtoupper($country);

        switch ($country) {
            case 'UK':
            case 'GB':
            case 'UNITED KINGDOM':
                return $this->calculateUKTaxes($purchasePrice, $options);
            case 'US':
            case 'USA':
            case 'UNITED STATES':
                return $this->calculateUSTaxes($purchasePrice, $options);
            default:
                return $this->calculateGenericTaxes($purchasePrice, $options);
        }
    }

    /**
     * Calculate UK property taxes including stamp duty
     *
     * @param float $purchasePrice
     * @param array $options
     * @return array
     */
    protected function calculateUKTaxes(float $purchasePrice, array $options): array
    {
        $buyerType = $options['buyer_type'] ?? 'home_mover';
        
        // Validate buyer type
        $validBuyerTypes = ['first_time_buyer', 'home_mover', 'additional_property'];
        if (!in_array($buyerType, $validBuyerTypes)) {
            $buyerType = 'home_mover';
        }

        $stampDutyData = $this->stampDutyCalculator->calculateStampDuty($purchasePrice, $buyerType);
        
        // UK-specific additional costs
        $legalFees = $this->estimateLegalFees($purchasePrice);
        $surveyFees = $this->estimateSurveyFees($purchasePrice);
        $landRegistryFees = $this->calculateLandRegistryFees($purchasePrice);

        $totalTax = $stampDutyData['stamp_duty'];
        $totalAdditionalCosts = $legalFees + $surveyFees + $landRegistryFees;
        $totalCost = $purchasePrice + $totalTax + $totalAdditionalCosts;

        return [
            'country' => 'United Kingdom',
            'purchase_price' => round($purchasePrice, 2),
            'stamp_duty' => $stampDutyData['stamp_duty'],
            'effective_tax_rate' => $stampDutyData['effective_tax_rate'],
            'buyer_type' => $buyerType,
            'additional_costs' => [
                'legal_fees' => round($legalFees, 2),
                'survey_fees' => round($surveyFees, 2),
                'land_registry_fees' => round($landRegistryFees, 2),
            ],
            'total_tax' => round($totalTax, 2),
            'total_additional_costs' => round($totalAdditionalCosts, 2),
            'total_cost' => round($totalCost, 2),
            'breakdown' => [
                'Purchase Price' => round($purchasePrice, 2),
                'Stamp Duty Land Tax (SDLT)' => $stampDutyData['stamp_duty'],
                'Legal Fees (est.)' => round($legalFees, 2),
                'Survey Fees (est.)' => round($surveyFees, 2),
                'Land Registry Fees' => round($landRegistryFees, 2),
                'Total Cost' => round($totalCost, 2),
            ],
        ];
    }

    /**
     * Calculate US property taxes
     *
     * @param float $purchasePrice
     * @param array $options
     * @return array
     */
    protected function calculateUSTaxes(float $purchasePrice, array $options): array
    {
        // US property tax is typically annual and varies by state/county
        // Using a generic 1.1% annual property tax rate as average
        $annualTaxRate = $options['annual_tax_rate'] ?? 0.011;
        $annualPropertyTax = $purchasePrice * $annualTaxRate;
        
        // Transfer taxes (varies by state, using 1% as estimate)
        $transferTaxRate = $options['transfer_tax_rate'] ?? 0.01;
        $transferTax = $purchasePrice * $transferTaxRate;
        
        // Recording fees and other closing costs
        $recordingFees = 500;
        $titleInsurance = $purchasePrice * 0.005;
        
        $totalTax = $transferTax;
        $totalAdditionalCosts = $recordingFees + $titleInsurance;
        $totalCost = $purchasePrice + $totalTax + $totalAdditionalCosts;

        return [
            'country' => 'United States',
            'purchase_price' => round($purchasePrice, 2),
            'transfer_tax' => round($transferTax, 2),
            'annual_property_tax' => round($annualPropertyTax, 2),
            'effective_tax_rate' => round(($transferTax / $purchasePrice) * 100, 2),
            'additional_costs' => [
                'recording_fees' => round($recordingFees, 2),
                'title_insurance' => round($titleInsurance, 2),
            ],
            'total_tax' => round($totalTax, 2),
            'total_additional_costs' => round($totalAdditionalCosts, 2),
            'total_cost' => round($totalCost, 2),
            'breakdown' => [
                'Purchase Price' => round($purchasePrice, 2),
                'Transfer Tax' => round($transferTax, 2),
                'Recording Fees' => round($recordingFees, 2),
                'Title Insurance (est.)' => round($titleInsurance, 2),
                'Total Upfront Cost' => round($totalCost, 2),
                'Annual Property Tax (est.)' => round($annualPropertyTax, 2),
            ],
        ];
    }

    /**
     * Calculate generic property taxes for other countries
     *
     * @param float $purchasePrice
     * @param array $options
     * @return array
     */
    protected function calculateGenericTaxes(float $purchasePrice, array $options): array
    {
        // Generic calculation using a 3% transfer tax estimate
        $taxRate = $options['tax_rate'] ?? 0.03;
        $propertyTax = $purchasePrice * $taxRate;
        
        // Generic additional costs
        $legalFees = $purchasePrice * 0.01;
        $registrationFees = 1000;
        
        $totalTax = $propertyTax;
        $totalAdditionalCosts = $legalFees + $registrationFees;
        $totalCost = $purchasePrice + $totalTax + $totalAdditionalCosts;

        return [
            'country' => $options['country_name'] ?? 'Other',
            'purchase_price' => round($purchasePrice, 2),
            'property_transfer_tax' => round($propertyTax, 2),
            'effective_tax_rate' => round(($propertyTax / $purchasePrice) * 100, 2),
            'additional_costs' => [
                'legal_fees' => round($legalFees, 2),
                'registration_fees' => round($registrationFees, 2),
            ],
            'total_tax' => round($totalTax, 2),
            'total_additional_costs' => round($totalAdditionalCosts, 2),
            'total_cost' => round($totalCost, 2),
            'breakdown' => [
                'Purchase Price' => round($purchasePrice, 2),
                'Property Transfer Tax (est.)' => round($propertyTax, 2),
                'Legal Fees (est.)' => round($legalFees, 2),
                'Registration Fees (est.)' => round($registrationFees, 2),
                'Total Cost' => round($totalCost, 2),
            ],
        ];
    }

    /**
     * Estimate legal fees based on property price (UK)
     */
    protected function estimateLegalFees(float $purchasePrice): float
    {
        if ($purchasePrice < 100000) {
            return 850;
        } elseif ($purchasePrice < 250000) {
            return 1200;
        } elseif ($purchasePrice < 500000) {
            return 1500;
        } elseif ($purchasePrice < 1000000) {
            return 2000;
        } else {
            return 2500;
        }
    }

    /**
     * Estimate survey fees based on property price (UK)
     */
    protected function estimateSurveyFees(float $purchasePrice): float
    {
        if ($purchasePrice < 100000) {
            return 400;
        } elseif ($purchasePrice < 250000) {
            return 600;
        } elseif ($purchasePrice < 500000) {
            return 900;
        } elseif ($purchasePrice < 1000000) {
            return 1200;
        } else {
            return 1500;
        }
    }

    /**
     * Calculate Land Registry fees (UK)
     */
    protected function calculateLandRegistryFees(float $purchasePrice): float
    {
        if ($purchasePrice <= 80000) {
            return 40;
        } elseif ($purchasePrice <= 100000) {
            return 80;
        } elseif ($purchasePrice <= 200000) {
            return 190;
        } elseif ($purchasePrice <= 500000) {
            return 270;
        } elseif ($purchasePrice <= 1000000) {
            return 540;
        } else {
            return 910;
        }
    }
}
