<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Services\ARTourService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ARTourController extends Controller
{
    protected ARTourService $arTourService;

    public function __construct(ARTourService $arTourService)
    {
        $this->arTourService = $arTourService;
    }

    /**
     * Get AR tour configuration for a property
     *
     * @param Property $property
     * @return JsonResponse
     */
    public function getConfig(Property $property): JsonResponse
    {
        if (!$this->arTourService->isARTourAvailable($property)) {
            return response()->json([
                'available' => false,
                'message' => 'AR tour is not available for this property.'
            ], 404);
        }

        $config = $this->arTourService->getARTourConfig($property);

        return response()->json([
            'available' => true,
            'config' => $config,
            'property' => [
                'id' => $property->id,
                'title' => $property->title,
                'location' => $property->location,
            ]
        ]);
    }

    /**
     * Check if AR tour is available for a property
     *
     * @param Property $property
     * @return JsonResponse
     */
    public function checkAvailability(Property $property): JsonResponse
    {
        $isAvailable = $this->arTourService->isARTourAvailable($property);
        $stats = $this->arTourService->getARTourStats($property);

        return response()->json([
            'available' => $isAvailable,
            'stats' => $stats,
        ]);
    }

    /**
     * Enable AR tour for a property (Admin/Agent only)
     *
     * @param Request $request
     * @param Property $property
     * @return JsonResponse
     */
    public function enable(Request $request, Property $property): JsonResponse
    {
        $settings = $request->validate([
            'ar_modes' => 'array',
            'enable_controls' => 'boolean',
            'auto_rotate' => 'boolean',
            'shadow_intensity' => 'numeric|min:0|max:2',
            'ar_model_scale' => 'numeric|min:0.1|max:10',
        ]);

        $success = $this->arTourService->enableARTour($property, $settings);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot enable AR tour. Property must have a 3D model first.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'AR tour enabled successfully.',
            'config' => $this->arTourService->getARTourConfig($property)
        ]);
    }

    /**
     * Disable AR tour for a property (Admin/Agent only)
     *
     * @param Property $property
     * @return JsonResponse
     */
    public function disable(Property $property): JsonResponse
    {
        $this->arTourService->disableARTour($property);

        return response()->json([
            'success' => true,
            'message' => 'AR tour disabled successfully.'
        ]);
    }

    /**
     * Update AR tour settings (Admin/Agent only)
     *
     * @param Request $request
     * @param Property $property
     * @return JsonResponse
     */
    public function updateSettings(Request $request, Property $property): JsonResponse
    {
        $settings = $request->validate([
            'ar_modes' => 'array',
            'enable_controls' => 'boolean',
            'auto_rotate' => 'boolean',
            'shadow_intensity' => 'numeric|min:0|max:2',
            'camera_orbit' => 'string',
            'min_camera_orbit' => 'string',
            'max_camera_orbit' => 'string',
            'interaction_prompt' => 'string',
            'ar_model_scale' => 'numeric|min:0.1|max:10',
            'ar_placement_guide' => 'string|in:floor,wall,ceiling',
        ]);

        $this->arTourService->updateARTourSettings($property, $settings);

        return response()->json([
            'success' => true,
            'message' => 'AR tour settings updated successfully.',
            'config' => $this->arTourService->getARTourConfig($property)
        ]);
    }
}
