<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Property;
use App\Services\VirtualStagingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VirtualStagingController extends Controller
{
    protected VirtualStagingService $stagingService;

    public function __construct(VirtualStagingService $stagingService)
    {
        $this->stagingService = $stagingService;
    }

    /**
     * Upload and optionally stage an image for a property
     *
     * @param Request $request
     * @param int $propertyId
     * @return JsonResponse
     */
    public function uploadImage(Request $request, int $propertyId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'staging_style' => 'nullable|string|in:' . implode(',', array_keys(VirtualStagingService::STAGING_STYLES)),
            'auto_stage' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $property = Property::findOrFail($propertyId);

        try {
            $image = $this->stagingService->uploadImage(
                $property,
                $request->file('image'),
                $request->input('staging_style'),
                $request->boolean('auto_stage', false)
            );

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => [
                    'image' => $this->formatImageResponse($image),
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Stage an existing image
     *
     * @param Request $request
     * @param int $imageId
     * @return JsonResponse
     */
    public function stageImage(Request $request, int $imageId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'staging_style' => 'required|string|in:' . implode(',', array_keys(VirtualStagingService::STAGING_STYLES)),
            'options' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $image = Image::findOrFail($imageId);

        if ($image->is_staged) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot stage an already staged image',
            ], 400);
        }

        try {
            $stagedImage = $this->stagingService->stageImage(
                $image,
                $request->input('staging_style'),
                $request->input('options', [])
            );

            return response()->json([
                'success' => true,
                'message' => 'Image staged successfully',
                'data' => [
                    'staged_image' => $this->formatImageResponse($stagedImage),
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to stage image',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get images for a property
     *
     * @param int $propertyId
     * @return JsonResponse
     */
    public function getPropertyImages(int $propertyId): JsonResponse
    {
        $property = Property::findOrFail($propertyId);
        $images = $this->stagingService->getPropertyImages($property, true);

        return response()->json([
            'success' => true,
            'data' => [
                'images' => $images->map(function ($image) {
                    return $this->formatImageResponse($image);
                }),
            ],
        ]);
    }

    /**
     * Get available staging styles
     *
     * @return JsonResponse
     */
    public function getStagingStyles(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'styles' => $this->stagingService->getStagingStyles(),
            ],
        ]);
    }

    /**
     * Delete an image
     *
     * @param int $imageId
     * @return JsonResponse
     */
    public function deleteImage(int $imageId): JsonResponse
    {
        $image = Image::findOrFail($imageId);

        try {
            $this->stagingService->deleteImage($image);

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Format image response
     *
     * @param Image $image
     * @return array
     */
    protected function formatImageResponse(Image $image): array
    {
        return [
            'id' => $image->image_id,
            'property_id' => $image->property_id,
            'file_name' => $image->file_name,
            'url' => $image->url,
            'is_staged' => $image->is_staged,
            'staging_style' => $image->staging_style,
            'staging_metadata' => $image->staging_metadata,
            'original_image_id' => $image->original_image_id,
            'has_staged_versions' => $image->hasStagedVersions(),
            'staged_versions' => $image->stagedVersions->map(function ($staged) {
                return [
                    'id' => $staged->image_id,
                    'url' => $staged->url,
                    'staging_style' => $staged->staging_style,
                    'staging_metadata' => $staged->staging_metadata,
                ];
            }),
            'created_at' => $image->created_at,
            'updated_at' => $image->updated_at,
        ];
    }
}
