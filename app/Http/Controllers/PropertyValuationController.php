<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyValuation;
use App\Services\NeuralNetworkValuationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PropertyValuationController extends Controller
{
    private NeuralNetworkValuationService $nnValuationService;
    
    public function __construct(NeuralNetworkValuationService $nnValuationService)
    {
        $this->nnValuationService = $nnValuationService;
    }
    
    /**
     * Generate a new valuation for a property
     */
    public function generateValuation(Request $request, Property $property)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'error' => 'Authentication required'
                ], 401);
            }
            
            // Generate valuation using neural network
            $valuation = $this->nnValuationService->createValuation(
                $property,
                $user->id,
                $user->current_team_id ?? $user->teams()->first()->id ?? 1
            );
            
            return response()->json([
                'success' => true,
                'valuation' => $valuation,
                'message' => 'Valuation generated successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Valuation generation failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to generate valuation',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get valuation history for a property
     */
    public function getValuationHistory(Property $property)
    {
        try {
            $valuations = PropertyValuation::where('property_id', $property->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            return response()->json([
                'success' => true,
                'valuations' => $valuations
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to fetch valuation history: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch valuation history'
            ], 500);
        }
    }
    
    /**
     * Get detailed valuation report
     */
    public function getDetailedReport(Property $property)
    {
        try {
            $report = $this->nnValuationService->getDetailedReport($property);
            
            return response()->json([
                'success' => true,
                'report' => $report
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to generate detailed report: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to generate detailed report'
            ], 500);
        }
    }
    
    /**
     * Train the neural network model with new data
     */
    public function trainModel(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Only allow admins to trigger training
            if (!$user || !$user->hasRole('admin')) {
                return response()->json([
                    'error' => 'Unauthorized. Admin access required.'
                ], 403);
            }
            
            $result = $this->nnValuationService->trainModel();
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            Log::error('Model training failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Model training failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
