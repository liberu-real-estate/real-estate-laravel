<?php

namespace App\Services;

use App\Models\Image;
use App\Models\Property;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VirtualStagingService
{
    /**
     * Available staging styles
     */
    public const STAGING_STYLES = [
        'modern' => 'Modern',
        'traditional' => 'Traditional',
        'minimalist' => 'Minimalist',
        'luxury' => 'Luxury',
        'industrial' => 'Industrial',
        'scandinavian' => 'Scandinavian',
        'contemporary' => 'Contemporary',
        'rustic' => 'Rustic',
    ];

    /**
     * Upload and optionally stage an image for a property
     *
     * @param Property $property
     * @param UploadedFile $file
     * @param string|null $stagingStyle
     * @param bool $autoStage
     * @return Image
     */
    public function uploadImage(Property $property, UploadedFile $file, ?string $stagingStyle = null, bool $autoStage = false): Image
    {
        // Store the original file
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('property-images', $fileName, 'public');

        // Create the image record
        $image = new Image([
            'property_id' => $property->id,
            'team_id' => $property->team_id,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'is_staged' => false,
        ]);
        $image->save();

        // Auto-stage if requested
        if ($autoStage && $stagingStyle) {
            $this->stageImage($image, $stagingStyle);
        }

        return $image;
    }

    /**
     * Create a virtually staged version of an image
     *
     * @param Image $originalImage
     * @param string $style
     * @param array $options
     * @return Image
     */
    public function stageImage(Image $originalImage, string $style, array $options = []): Image
    {
        // Validate the staging style
        if (!isset(self::STAGING_STYLES[$style])) {
            throw new \InvalidArgumentException("Invalid staging style: {$style}");
        }

        // In a real implementation, this would call an AI service (e.g., OpenAI, Stable Diffusion, etc.)
        // For now, we'll simulate the staging by copying the original image with metadata
        $stagedImage = $this->mockStageImage($originalImage, $style, $options);

        return $stagedImage;
    }

    /**
     * Mock implementation of image staging
     * In production, this would integrate with an AI service
     *
     * @param Image $originalImage
     * @param string $style
     * @param array $options
     * @return Image
     */
    protected function mockStageImage(Image $originalImage, string $style, array $options = []): Image
    {
        // Copy the original file to simulate staging
        $originalPath = $originalImage->file_path;
        $extension = pathinfo($originalPath, PATHINFO_EXTENSION);
        $stagedFileName = Str::uuid() . '_staged.' . $extension;
        $stagedPath = 'property-images/' . $stagedFileName;

        // Copy file in storage
        Storage::disk('public')->copy($originalPath, $stagedPath);

        // Create staged image record
        $stagedImage = new Image([
            'property_id' => $originalImage->property_id,
            'team_id' => $originalImage->team_id,
            'file_path' => $stagedPath,
            'file_name' => 'staged_' . $originalImage->file_name,
            'mime_type' => $originalImage->mime_type,
            'is_staged' => true,
            'original_image_id' => $originalImage->image_id,
            'staging_style' => $style,
            'staging_provider' => 'mock',
            'staging_metadata' => array_merge([
                'style_name' => self::STAGING_STYLES[$style],
                'staged_at' => now()->toIso8601String(),
                'mock_staging' => true,
                'description' => "Virtually staged with {$style} style",
            ], $options),
        ]);
        $stagedImage->save();

        return $stagedImage;
    }

    /**
     * Get all available staging styles
     *
     * @return array
     */
    public function getStagingStyles(): array
    {
        return self::STAGING_STYLES;
    }

    /**
     * Delete an image and its staged versions
     *
     * @param Image $image
     * @return bool
     */
    public function deleteImage(Image $image): bool
    {
        // Delete staged versions if this is an original
        if (!$image->is_staged) {
            foreach ($image->stagedVersions as $stagedVersion) {
                $this->deleteImageFile($stagedVersion);
                $stagedVersion->delete();
            }
        }

        // Delete the file
        $this->deleteImageFile($image);

        // Delete the record
        return $image->delete();
    }

    /**
     * Delete the physical file from storage
     *
     * @param Image $image
     * @return bool
     */
    protected function deleteImageFile(Image $image): bool
    {
        if ($image->file_path && Storage::disk('public')->exists($image->file_path)) {
            return Storage::disk('public')->delete($image->file_path);
        }
        return false;
    }

    /**
     * Get images for a property with staging information
     *
     * @param Property $property
     * @param bool $includeStaged
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPropertyImages(Property $property, bool $includeStaged = true)
    {
        $query = $property->images();

        if (!$includeStaged) {
            $query->where('is_staged', false);
        }

        return $query->with(['stagedVersions', 'originalImage'])->get();
    }
}
