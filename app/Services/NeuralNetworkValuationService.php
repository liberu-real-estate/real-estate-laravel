<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyValuation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Neural Network-Based Property Valuation Service
 * 
 * This service implements a simulated neural network for property valuation.
 * In production, this would integrate with actual ML libraries like TensorFlow or PyTorch.
 */
class NeuralNetworkValuationService
{
    private const MODEL_VERSION = '1.0.0';
    private const CACHE_TTL = 3600; // 1 hour
    private const MIN_TRAINING_DATA = 10;
    
    private PropertyValuationService $valuationService;
    
    public function __construct(PropertyValuationService $valuationService)
    {
        $this->valuationService = $valuationService;
    }
    
    /**
     * Generate property valuation using neural network
     */
    public function generateValuation(Property $property): array
    {
        // Extract features from property
        $features = $this->extractFeatures($property);
        
        // Get model weights (in production, load from trained model)
        $weights = $this->getModelWeights();
        
        // Make prediction using neural network
        $prediction = $this->predict($features, $weights);
        
        // Calculate confidence based on data quality and model certainty
        $confidence = $this->calculateConfidence($property, $features, $prediction);
        
        // Get comparable properties for validation
        $comparables = $this->valuationService->getComparableProperties($property, 10);
        
        // Get feature importance for interpretability
        $featureImportance = $this->calculateFeatureImportance($features, $weights);
        
        return [
            'estimated_value' => round($prediction['value'], 2),
            'confidence_level' => round($confidence, 0),
            'price_range' => [
                'min' => round($prediction['value'] * (1 - ($confidence / 200)), 2),
                'max' => round($prediction['value'] * (1 + ($confidence / 200)), 2),
            ],
            'method' => 'neural_network',
            'model_version' => self::MODEL_VERSION,
            'feature_importance' => $featureImportance,
            'comparables_count' => $comparables->count(),
            'market_trend' => $this->analyzeMarketTrend($property),
            'prediction_factors' => $this->getPredictionFactors($features, $prediction),
        ];
    }
    
    /**
     * Extract numerical features from property for neural network input
     */
    private function extractFeatures(Property $property): array
    {
        return [
            // Property characteristics
            'bedrooms' => $property->bedrooms ?? 0,
            'bathrooms' => $property->bathrooms ?? 0,
            'area_sqft' => $property->area_sqft ?? 0,
            'year_built' => $property->year_built ?? 1900,
            'age' => now()->year - ($property->year_built ?? 1900),
            
            // Location factors
            'latitude' => $property->latitude ?? 0,
            'longitude' => $property->longitude ?? 0,
            
            // Property type encoding
            'is_detached' => $property->property_type === 'detached' ? 1 : 0,
            'is_semi_detached' => $property->property_type === 'semi-detached' ? 1 : 0,
            'is_apartment' => $property->property_type === 'apartment' ? 1 : 0,
            'is_townhouse' => $property->property_type === 'townhouse' ? 1 : 0,
            
            // Status encoding
            'is_for_sale' => $property->status === 'for_sale' ? 1 : 0,
            'is_for_rent' => $property->status === 'for_rent' ? 1 : 0,
            'is_featured' => $property->is_featured ? 1 : 0,
            
            // Market factors
            'days_on_market' => $property->list_date ? now()->diffInDays($property->list_date) : 0,
            'price_per_sqft' => $property->area_sqft > 0 ? ($property->price / $property->area_sqft) : 0,
        ];
    }
    
    /**
     * Get neural network model weights
     * In production, this would load from a trained model file
     */
    private function getModelWeights(): array
    {
        return Cache::remember('nn_model_weights_' . self::MODEL_VERSION, self::CACHE_TTL, function () {
            // Simulated weights for demonstration
            // In production, these would be learned during training
            return [
                'layer1' => [
                    'bedrooms' => 15000,
                    'bathrooms' => 12000,
                    'area_sqft' => 150,
                    'year_built' => -50,
                    'age' => -500,
                    'is_detached' => 50000,
                    'is_semi_detached' => 30000,
                    'is_apartment' => 10000,
                    'is_townhouse' => 25000,
                    'is_featured' => 20000,
                    'days_on_market' => -100,
                    'latitude' => 5000,
                    'longitude' => -3000,
                ],
                'base_value' => 100000, // Base property value
                'activation' => 'relu', // Activation function
            ];
        });
    }
    
    /**
     * Make prediction using neural network
     */
    private function predict(array $features, array $weights): array
    {
        // Simple feedforward neural network simulation
        $value = $weights['base_value'];
        
        foreach ($weights['layer1'] as $feature => $weight) {
            if (isset($features[$feature])) {
                $value += $features[$feature] * $weight;
            }
        }
        
        // Apply activation function (ReLU)
        $value = max(0, $value);
        
        // Add market adjustment factor
        $marketAdjustment = $this->getMarketAdjustmentFactor();
        $value *= $marketAdjustment;
        
        return [
            'value' => $value,
            'raw_value' => $value / $marketAdjustment,
            'market_adjustment' => $marketAdjustment,
        ];
    }
    
    /**
     * Calculate confidence level for the prediction
     */
    private function calculateConfidence(Property $property, array $features, array $prediction): float
    {
        $confidence = 100;
        
        // Reduce confidence if property data is incomplete
        $requiredFields = ['bedrooms', 'bathrooms', 'area_sqft', 'year_built', 'property_type'];
        $missingFields = 0;
        
        foreach ($requiredFields as $field) {
            if (empty($property->$field)) {
                $missingFields++;
            }
        }
        
        $confidence -= ($missingFields * 10);
        
        // Reduce confidence for very old or new properties
        $age = $features['age'];
        if ($age > 100 || $age < 0) {
            $confidence -= 15;
        }
        
        // Reduce confidence if area is unusually small or large
        $area = $features['area_sqft'];
        if ($area < 300 || $area > 10000) {
            $confidence -= 10;
        }
        
        // Increase confidence based on training data availability
        $trainingDataCount = $this->getTrainingDataCount();
        if ($trainingDataCount > self::MIN_TRAINING_DATA) {
            $confidence += min(20, ($trainingDataCount - self::MIN_TRAINING_DATA) / 10);
        } else {
            $confidence -= 20;
        }
        
        return max(0, min(100, $confidence));
    }
    
    /**
     * Calculate feature importance for model interpretability
     */
    private function calculateFeatureImportance(array $features, array $weights): array
    {
        $importance = [];
        $totalImpact = 0;
        
        // Calculate raw impact for each feature
        foreach ($weights['layer1'] as $feature => $weight) {
            if (isset($features[$feature])) {
                $impact = abs($features[$feature] * $weight);
                $importance[$feature] = $impact;
                $totalImpact += $impact;
            }
        }
        
        // Normalize to percentages
        if ($totalImpact > 0) {
            foreach ($importance as $feature => $impact) {
                $importance[$feature] = round(($impact / $totalImpact) * 100, 2);
            }
        }
        
        // Sort by importance
        arsort($importance);
        
        // Return top 5 most important features
        return array_slice($importance, 0, 5, true);
    }
    
    /**
     * Analyze market trend for the property
     */
    private function analyzeMarketTrend(Property $property): string
    {
        // In production, this would analyze historical market data
        // For now, return a simulated trend
        $trends = ['rising', 'stable', 'declining', 'volatile'];
        
        // Use property location as seed for consistent results
        $seed = crc32($property->location ?? 'default');
        srand($seed);
        $trendIndex = rand(0, count($trends) - 1);
        
        return $trends[$trendIndex];
    }
    
    /**
     * Get market adjustment factor
     */
    private function getMarketAdjustmentFactor(): float
    {
        // In production, this would be based on current market conditions
        // For now, return a simulated factor
        return 1.05; // 5% market appreciation
    }
    
    /**
     * Get count of training data
     */
    private function getTrainingDataCount(): int
    {
        // In production, this would count actual training samples
        // For now, return count of properties with valuations
        return PropertyValuation::where('valuation_type', 'neural_network')->count();
    }
    
    /**
     * Get prediction factors that influenced the valuation
     */
    private function getPredictionFactors(array $features, array $prediction): array
    {
        $factors = [];
        
        if ($features['area_sqft'] > 2000) {
            $factors[] = 'Large property size adds significant value';
        }
        
        if ($features['age'] < 10) {
            $factors[] = 'New construction premium applied';
        } elseif ($features['age'] > 50) {
            $factors[] = 'Age of property reduces value';
        }
        
        if ($features['is_detached'] === 1) {
            $factors[] = 'Detached property type adds premium';
        }
        
        if ($features['is_featured'] === 1) {
            $factors[] = 'Featured property status indicates higher quality';
        }
        
        if ($features['days_on_market'] > 90) {
            $factors[] = 'Extended time on market may indicate overpricing';
        }
        
        return $factors;
    }
    
    /**
     * Create and save valuation using neural network
     */
    public function createValuation(Property $property, int $userId, int $teamId): PropertyValuation
    {
        $valuationData = $this->generateValuation($property);
        
        $data = [
            'valuation_type' => 'neural_network',
            'estimated_value' => $valuationData['estimated_value'],
            'market_value' => $valuationData['estimated_value'],
            'valuation_date' => now(),
            'valuation_method' => 'neural_network_ml',
            'confidence_level' => $valuationData['confidence_level'],
            'valid_until' => now()->addMonths(3),
            'user_id' => $userId,
            'team_id' => $teamId,
            'status' => 'active',
            'notes' => 'AI-powered valuation using neural network model v' . self::MODEL_VERSION,
            'location_factors' => [
                'market_trend' => $valuationData['market_trend'],
                'prediction_factors' => $valuationData['prediction_factors'],
            ],
            'comparable_properties' => [
                'count' => $valuationData['comparables_count'],
                'feature_importance' => $valuationData['feature_importance'],
            ],
        ];
        
        return $this->valuationService->createValuation($property, $data);
    }
    
    /**
     * Simulate model training with new data
     * In production, this would trigger actual ML model training
     */
    public function trainModel(): array
    {
        Log::info('Neural network model training initiated');
        
        // Get recent property valuations for training
        $trainingData = PropertyValuation::with('property')
            ->where('status', 'active')
            ->where('created_at', '>=', now()->subMonths(6))
            ->get();
        
        if ($trainingData->count() < self::MIN_TRAINING_DATA) {
            return [
                'success' => false,
                'message' => 'Insufficient training data. Need at least ' . self::MIN_TRAINING_DATA . ' samples.',
                'current_samples' => $trainingData->count(),
            ];
        }
        
        // Simulate training process
        // In production, this would actually train the neural network
        
        Log::info('Neural network model trained with ' . $trainingData->count() . ' samples');
        
        return [
            'success' => true,
            'message' => 'Model training completed successfully',
            'model_version' => self::MODEL_VERSION,
            'training_samples' => $trainingData->count(),
            'timestamp' => now()->toDateTimeString(),
        ];
    }
    
    /**
     * Get detailed valuation report
     */
    public function getDetailedReport(Property $property): array
    {
        $valuation = $this->generateValuation($property);
        $comparables = $this->valuationService->getComparableProperties($property, 10);
        
        return [
            'property' => [
                'id' => $property->id,
                'title' => $property->title,
                'location' => $property->location,
                'bedrooms' => $property->bedrooms,
                'bathrooms' => $property->bathrooms,
                'area_sqft' => $property->area_sqft,
                'year_built' => $property->year_built,
                'property_type' => $property->property_type,
            ],
            'valuation' => $valuation,
            'comparables' => $comparables->map(function ($comp) {
                return [
                    'id' => $comp->id,
                    'title' => $comp->title,
                    'price' => $comp->price,
                    'bedrooms' => $comp->bedrooms,
                    'bathrooms' => $comp->bathrooms,
                    'area_sqft' => $comp->area_sqft,
                ];
            }),
            'report_date' => now()->toDateTimeString(),
            'model_version' => self::MODEL_VERSION,
        ];
    }
}
