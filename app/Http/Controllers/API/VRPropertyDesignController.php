<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\VRDesign;
use App\Services\VRPropertyDesignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VRPropertyDesignController extends Controller
{
    protected VRPropertyDesignService $service;

    public function __construct(VRPropertyDesignService $service)
    {
        $this->service = $service;
    }

    /**
     * Get all available design styles.
     */
    public function getStyles(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'styles' => $this->service->getDesignStyles(),
            ],
        ]);
    }

    /**
     * Get all furniture categories.
     */
    public function getFurnitureCategories(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'categories' => $this->service->getFurnitureCategories(),
            ],
        ]);
    }

    /**
     * Get all room types.
     */
    public function getRoomTypes(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'room_types' => $this->service->getRoomTypes(),
            ],
        ]);
    }

    /**
     * Get supported VR devices.
     */
    public function getSupportedDevices(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'devices' => $this->service->getSupportedDevices(),
            ],
        ]);
    }

    /**
     * Get all designs for a property.
     */
    public function getPropertyDesigns(Request $request, int $propertyId): JsonResponse
    {
        $property = Property::findOrFail($propertyId);

        $publicOnly = $request->boolean('public_only', false);
        $designs = $this->service->getPropertyDesigns($property, $publicOnly);

        return response()->json([
            'success' => true,
            'data' => [
                'designs' => $designs,
                'count' => count($designs),
            ],
        ]);
    }

    /**
     * Get a specific design.
     */
    public function getDesign(int $designId): JsonResponse
    {
        $design = $this->service->getDesign($designId);

        if (!$design) {
            return response()->json([
                'success' => false,
                'message' => 'Design not found',
            ], 404);
        }

        // Increment view count
        $design->incrementViewCount();

        return response()->json([
            'success' => true,
            'data' => [
                'design' => $design,
            ],
        ]);
    }

    /**
     * Create a new VR design.
     */
    public function createDesign(Request $request, int $propertyId): JsonResponse
    {
        $property = Property::findOrFail($propertyId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'style' => ['nullable', 'string', Rule::in(array_keys($this->service->getDesignStyles()))],
            'design_data' => 'required|array',
            'is_public' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $design = $this->service->createDesign(
                $property,
                $request->user(),
                $request->input('name'),
                $request->input('design_data'),
                $request->input('description'),
                $request->input('style'),
                $request->boolean('is_public', false)
            );

            return response()->json([
                'success' => true,
                'message' => 'VR design created successfully',
                'data' => [
                    'design' => $design,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create design: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing VR design.
     */
    public function updateDesign(Request $request, int $designId): JsonResponse
    {
        $design = VRDesign::findOrFail($designId);

        // Check authorization
        if ($design->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:1000',
            'style' => ['nullable', 'string', Rule::in(array_keys($this->service->getDesignStyles()))],
            'design_data' => 'sometimes|array',
            'room_layout' => 'sometimes|array',
            'furniture_items' => 'sometimes|array',
            'materials' => 'sometimes|array',
            'lighting' => 'sometimes|array',
            'is_public' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $design = $this->service->updateDesign($design, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'VR design updated successfully',
                'data' => [
                    'design' => $design,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update design: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a VR design.
     */
    public function deleteDesign(Request $request, int $designId): JsonResponse
    {
        $design = VRDesign::findOrFail($designId);

        // Check authorization
        if ($design->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        try {
            $this->service->deleteDesign($design);

            return response()->json([
                'success' => true,
                'message' => 'VR design deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete design: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add furniture to a design.
     */
    public function addFurniture(Request $request, int $designId): JsonResponse
    {
        $design = VRDesign::findOrFail($designId);

        // Check authorization
        if ($design->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'category' => 'required|string',
            'type' => 'required|string',
            'position' => 'required|array',
            'position.*' => 'numeric',
            'rotation' => 'sometimes|array',
            'rotation.*' => 'numeric',
            'scale' => 'sometimes|array',
            'scale.*' => 'numeric',
            'material' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $design = $this->service->addFurniture(
                $design,
                $request->input('category'),
                $request->input('type'),
                $request->input('position'),
                $request->input('rotation', [0, 0, 0]),
                $request->input('scale', [1, 1, 1]),
                $request->input('material', [])
            );

            return response()->json([
                'success' => true,
                'message' => 'Furniture added successfully',
                'data' => [
                    'design' => $design,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add furniture: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove furniture from a design.
     */
    public function removeFurniture(Request $request, int $designId, string $furnitureId): JsonResponse
    {
        $design = VRDesign::findOrFail($designId);

        // Check authorization
        if ($design->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        try {
            $design = $this->service->removeFurniture($design, $furnitureId);

            return response()->json([
                'success' => true,
                'message' => 'Furniture removed successfully',
                'data' => [
                    'design' => $design,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove furniture: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clone a design.
     */
    public function cloneDesign(Request $request, int $designId): JsonResponse
    {
        $design = VRDesign::findOrFail($designId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $clonedDesign = $this->service->cloneDesign(
                $design,
                $request->user(),
                $request->input('name')
            );

            return response()->json([
                'success' => true,
                'message' => 'Design cloned successfully',
                'data' => [
                    'design' => $clonedDesign,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clone design: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all available templates.
     */
    public function getTemplates(Request $request): JsonResponse
    {
        $style = $request->input('style');
        $templates = $this->service->getTemplates($style);

        return response()->json([
            'success' => true,
            'data' => [
                'templates' => $templates,
                'count' => count($templates),
            ],
        ]);
    }

    /**
     * Upload thumbnail for a design.
     */
    public function uploadThumbnail(Request $request, int $designId): JsonResponse
    {
        $design = VRDesign::findOrFail($designId);

        // Check authorization
        if ($design->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $design = $this->service->uploadThumbnail($design, $request->file('thumbnail'));

            return response()->json([
                'success' => true,
                'message' => 'Thumbnail uploaded successfully',
                'data' => [
                    'design' => $design,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload thumbnail: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export design data.
     */
    public function exportDesign(Request $request, int $designId): JsonResponse
    {
        $design = VRDesign::findOrFail($designId);

        $format = $request->input('format', 'json');

        try {
            $data = $this->service->exportDesign($design, $format);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export design: ' . $e->getMessage(),
            ], 500);
        }
    }
}
