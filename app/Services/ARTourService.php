<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Storage;

/**
 * Service for handling AR (Augmented Reality) tour functionality
 */
class ARTourService
{
    /**
     * Check if a property has AR tour available
     *
     * @param Property $property
     * @return bool
     */
    public function isARTourAvailable(Property $property): bool
    {
        return $property->ar_tour_enabled 
            && $property->hasMedia('3d_models');
    }

    /**
     * Get AR tour configuration for a property
     *
     * @param Property $property
     * @return array
     */
    public function getARTourConfig(Property $property): array
    {
        $settings = $property->ar_tour_settings ?? [];
        
        return [
            'model_url' => $property->getFirstMediaUrl('3d_models'),
            'scale' => $property->ar_model_scale ?? 1.0,
            'placement_guide' => $property->ar_placement_guide ?? 'floor',
            'ar_modes' => $settings['ar_modes'] ?? ['webxr', 'scene-viewer', 'quick-look'],
            'enable_controls' => $settings['enable_controls'] ?? true,
            'auto_rotate' => $settings['auto_rotate'] ?? true,
            'shadow_intensity' => $settings['shadow_intensity'] ?? 1,
            'camera_orbit' => $settings['camera_orbit'] ?? '0deg 75deg 2.5m',
            'min_camera_orbit' => $settings['min_camera_orbit'] ?? 'auto auto 1m',
            'max_camera_orbit' => $settings['max_camera_orbit'] ?? 'auto auto 10m',
            'interaction_prompt' => $settings['interaction_prompt'] ?? 'auto',
        ];
    }

    /**
     * Enable AR tour for a property
     *
     * @param Property $property
     * @param array $settings
     * @return bool
     */
    public function enableARTour(Property $property, array $settings = []): bool
    {
        if (!$property->hasMedia('3d_models')) {
            return false;
        }

        $property->update([
            'ar_tour_enabled' => true,
            'ar_tour_settings' => array_merge($this->getDefaultSettings(), $settings),
        ]);

        return true;
    }

    /**
     * Disable AR tour for a property
     *
     * @param Property $property
     * @return bool
     */
    public function disableARTour(Property $property): bool
    {
        $property->update([
            'ar_tour_enabled' => false,
        ]);

        return true;
    }

    /**
     * Update AR tour settings
     *
     * @param Property $property
     * @param array $settings
     * @return bool
     */
    public function updateARTourSettings(Property $property, array $settings): bool
    {
        $currentSettings = $property->ar_tour_settings ?? [];
        
        $property->update([
            'ar_tour_settings' => array_merge($currentSettings, $settings),
        ]);

        return true;
    }

    /**
     * Get default AR tour settings
     *
     * @return array
     */
    protected function getDefaultSettings(): array
    {
        return [
            'ar_modes' => ['webxr', 'scene-viewer', 'quick-look'],
            'enable_controls' => true,
            'auto_rotate' => true,
            'shadow_intensity' => 1,
            'camera_orbit' => '0deg 75deg 2.5m',
            'min_camera_orbit' => 'auto auto 1m',
            'max_camera_orbit' => 'auto auto 10m',
            'interaction_prompt' => 'auto',
        ];
    }

    /**
     * Validate 3D model for AR compatibility
     *
     * @param string $filePath
     * @return array
     */
    public function validate3DModel(string $filePath): array
    {
        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        $supportedFormats = ['glb', 'gltf'];
        
        if (!in_array($fileExtension, $supportedFormats)) {
            return [
                'valid' => false,
                'message' => 'Unsupported file format. Please use GLB or GLTF format for AR compatibility.'
            ];
        }

        // Check file size only if file exists (recommend under 10MB for good mobile performance)
        if (file_exists($filePath) && is_file($filePath)) {
            $fileSizeMB = filesize($filePath) / (1024 * 1024);
            
            if ($fileSizeMB > 10) {
                return [
                    'valid' => true,
                    'warning' => 'File size is over 10MB. This may cause slow loading on mobile devices.'
                ];
            }
        }

        return [
            'valid' => true,
            'message' => 'Model is compatible with AR.'
        ];
    }

    /**
     * Get AR tour statistics for a property
     *
     * @param Property $property
     * @return array
     */
    public function getARTourStats(Property $property): array
    {
        // This can be extended to track AR tour usage
        return [
            'ar_enabled' => $property->ar_tour_enabled,
            'has_3d_model' => $property->hasMedia('3d_models'),
            'is_available' => $this->isARTourAvailable($property),
        ];
    }
}
