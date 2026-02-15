<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Http;

class AIInvestmentAnalysisService
{
    private $marketAnalysisService;
    private $propertyRecommendationService;
    private $propertyValuationService;

    public function __construct(
        MarketAnalysisService $marketAnalysisService,
        PropertyRecommendationService $propertyRecommendationService,
        PropertyValuationService $propertyValuationService
    ) {
        $this->marketAnalysisService = $marketAnalysisService;
        $this->propertyRecommendationService = $propertyRecommendationService;
        $this->propertyValuationService = $propertyValuationService;
    }

    public function analyzeInvestment(Property $property)
    {
        try {
            $marketAnalysis = $this->marketAnalysisService->generateMarketAnalysis(now()->subYear(), now());
            $valuation = $this->propertyValuationService->calculateValuation($property);
            
            // Only get recommendations if property has a user
            $recommendations = $property->user ? $this->propertyRecommendationService->getRecommendations($property->user) : [];

            // Implement machine learning prediction here
            $prediction = $this->predictInvestmentPerformance($property, $marketAnalysis);
            
            // Calculate additional metrics
            $cashFlowAnalysis = $this->calculateCashFlowAnalysis($property);
            $marketPosition = $this->calculateMarketPosition($property, $marketAnalysis);

            return [
                'market_analysis' => $marketAnalysis,
                'valuation' => $valuation,
                'recommendations' => $recommendations,
                'prediction' => $prediction,
                'cash_flow_analysis' => $cashFlowAnalysis,
                'market_position' => $marketPosition,
            ];
        } catch (\Exception $e) {
            \Log::error('Investment analysis failed: ' . $e->getMessage());
            return $this->getDefaultAnalysis($property);
        }
    }

    private function predictInvestmentPerformance(Property $property, array $marketAnalysis)
    {
        // Placeholder for machine learning prediction
        // In a real implementation, you would use a trained model to make predictions
        $predictedROI = $this->calculatePredictedROI($property, $marketAnalysis);
        $riskScore = $this->calculateRiskScore($property, $marketAnalysis);

        return [
            'predicted_roi' => $predictedROI,
            'risk_score' => $riskScore,
        ];
    }

    private function calculatePredictedROI(Property $property, array $marketAnalysis)
    {
        // Simplified ROI calculation based on market trends
        $marketDataItem = $marketAnalysis['market_data']->where('property_type', $property->property_type)->first();
        
        if (!$marketDataItem || !isset($marketDataItem->avg_price)) {
            // Default to 3% appreciation if no market data available
            $appreciationRate = 3;
        } else {
            // Calculate appreciation based on market average vs property price
            $marketAverage = $marketDataItem->avg_price;
            $appreciationRate = $property->price > 0 ? (($marketAverage - $property->price) / $property->price) * 100 : 3;
            // Cap appreciation between -10% and 20%
            $appreciationRate = max(-10, min(20, $appreciationRate));
        }
        
        $predictedValue = $property->price * (1 + ($appreciationRate / 100));
        $predictedROI = (($predictedValue - $property->price) / $property->price) * 100;

        return round($predictedROI, 2);
    }

    private function calculateRiskScore(Property $property, array $marketAnalysis)
    {
        // Simplified risk score calculation
        $marketVolatility = $this->calculateMarketVolatility($marketAnalysis);
        $propertyAge = now()->year - $property->year_built;
        $locationFactor = $this->getLocationFactor($property->location);

        $riskScore = ($marketVolatility * 0.4) + ($propertyAge * 0.3) + ($locationFactor * 0.3);

        return min(max(round($riskScore, 2), 1), 10); // Ensure risk score is between 1 and 10
    }

    private function calculateMarketVolatility(array $marketAnalysis)
    {
        // Simplified market volatility calculation
        if (empty($marketAnalysis['market_data']) || $marketAnalysis['market_data']->isEmpty()) {
            return 5; // Default medium volatility
        }
        
        $prices = $marketAnalysis['market_data']->pluck('avg_price')->filter();
        
        if ($prices->isEmpty() || $prices->count() < 2) {
            return 5; // Default medium volatility
        }
        
        $stdDev = $this->calculateStandardDeviation($prices->toArray());
        $mean = $prices->avg();
        
        if ($mean === 0) {
            return 5; // Default medium volatility
        }
        
        $volatility = ($stdDev / $mean) * 100;
        return min(10, max(1, $volatility)); // Ensure between 1 and 10
    }

    private function getLocationFactor(string $location)
    {
        // Placeholder for location-based risk factor
        // In a real implementation, this would be based on more comprehensive data
        $locationFactors = [
            'London' => 2,
            'Manchester' => 3,
            'Birmingham' => 4,
            // Add more locations as needed
        ];

        return $locationFactors[$location] ?? 5; // Default to medium risk if location not found
    }
    
    private function calculateCashFlowAnalysis(Property $property)
    {
        // Estimate rental yield if not set
        $estimatedAnnualRent = $property->price * 0.05; // 5% default yield
        $estimatedExpenses = $estimatedAnnualRent * 0.30; // 30% expenses
        $netCashFlow = $estimatedAnnualRent - $estimatedExpenses;
        
        return [
            'estimated_annual_rent' => round($estimatedAnnualRent, 2),
            'estimated_expenses' => round($estimatedExpenses, 2),
            'net_cash_flow' => round($netCashFlow, 2),
            'cash_on_cash_return' => round(($netCashFlow / $property->price) * 100, 2),
        ];
    }
    
    private function calculateMarketPosition(Property $property, array $marketAnalysis)
    {
        if (empty($marketAnalysis['market_data']) || $marketAnalysis['market_data']->isEmpty()) {
            return [
                'position' => 'average',
                'price_vs_market' => 0,
                'competitive_advantage' => 'Limited market data available',
            ];
        }
        
        $marketDataItem = $marketAnalysis['market_data']->where('property_type', $property->property_type)->first();
        
        if (!$marketDataItem) {
            return [
                'position' => 'average',
                'price_vs_market' => 0,
                'competitive_advantage' => 'No comparable properties found',
            ];
        }
        
        $avgMarketPrice = $marketDataItem->avg_price;
        $priceVsMarket = (($property->price - $avgMarketPrice) / $avgMarketPrice) * 100;
        
        $position = 'average';
        $advantage = 'Property is priced at market average';
        
        if ($priceVsMarket < -10) {
            $position = 'excellent';
            $advantage = 'Property is priced significantly below market average - great investment opportunity';
        } elseif ($priceVsMarket < -5) {
            $position = 'good';
            $advantage = 'Property is priced below market average - good value';
        } elseif ($priceVsMarket > 10) {
            $position = 'premium';
            $advantage = 'Property is priced above market average - premium location or features';
        } elseif ($priceVsMarket > 5) {
            $position = 'above_average';
            $advantage = 'Property is priced slightly above market average';
        }
        
        return [
            'position' => $position,
            'price_vs_market' => round($priceVsMarket, 2),
            'competitive_advantage' => $advantage,
        ];
    }
    
    private function calculateStandardDeviation(array $values)
    {
        $count = count($values);
        if ($count < 2) {
            return 0;
        }
        
        $mean = array_sum($values) / $count;
        $variance = 0;
        
        foreach ($values as $value) {
            $variance += pow($value - $mean, 2);
        }
        
        return sqrt($variance / $count);
    }
    
    private function getDefaultAnalysis(Property $property)
    {
        return [
            'market_analysis' => [
                'market_data' => collect([]),
                'price_per_sqft' => collect([]),
            ],
            'valuation' => $property->price,
            'recommendations' => [],
            'prediction' => [
                'predicted_roi' => 5.0,
                'risk_score' => 5.0,
            ],
            'cash_flow_analysis' => $this->calculateCashFlowAnalysis($property),
            'market_position' => [
                'position' => 'average',
                'price_vs_market' => 0,
                'competitive_advantage' => 'Limited market data available',
            ],
        ];
    }
}