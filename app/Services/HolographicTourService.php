<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Service for managing holographic property tours
 * 
 * This service handles integration with holographic display providers
 * and manages holographic tour content for properties.
 */
class HolographicTourService
{
    protected string $apiKey;
    protected string $baseUri;
    protected string $provider;

    public function __construct()
    {
        $this->apiKey = config('services.holographic.api_key', '');
        $this->baseUri = config('services.holographic.base_uri', '');
        $this->provider = config('services.holographic.provider', 'looking_glass');
    }

    /**
     * Generate holographic tour for a property
     *
     * @param Property $property
     * @return array|null
     */
    public function generateHolographicTour(Property $property): ?array
    {
        try {
            // Get 3D model URL if available
            $model3dUrl = $property->model_3d_url ?? $property->getFirstMediaUrl('3d_models');
            
            if (empty($model3dUrl)) {
                Log::warning("No 3D model available for property {$property->id}");
                return null;
            }

            // Prepare holographic tour data
            $tourData = [
                'property_id' => $property->id,
                'model_url' => $model3dUrl,
                'title' => $property->title,
                'description' => $property->description,
                'display_type' => 'hologram',
                'resolution' => '4k',
                'viewing_angles' => [
                    'front', 'back', 'left', 'right', 'top', 'interior'
                ],
            ];

            // Cache the tour data
            $cacheKey = "holographic_tour_{$property->id}";
            Cache::put($cacheKey, $tourData, now()->addDays(7));

            return $tourData;
        } catch (\Exception $e) {
            Log::error("Failed to generate holographic tour for property {$property->id}: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Get holographic tour URL for a property
     *
     * @param Property $property
     * @return string|null
     */
    public function getHolographicTourUrl(Property $property): ?string
    {
        if ($property->holographic_tour_url) {
            return $property->holographic_tour_url;
        }

        $tourData = $this->generateHolographicTour($property);
        
        if ($tourData) {
            // Generate a URL for the holographic viewer
            $url = route('property.holographic-tour', ['property' => $property->id]);
            
            // Update property with holographic tour URL
            $property->update([
                'holographic_tour_url' => $url,
                'holographic_provider' => $this->provider,
                'holographic_metadata' => $tourData,
                'holographic_enabled' => true,
            ]);
            
            return $url;
        }

        return null;
    }

    /**
     * Check if holographic tour is available for property
     *
     * @param Property $property
     * @return bool
     */
    public function isAvailable(Property $property): bool
    {
        return $property->holographic_enabled 
            && !empty($property->holographic_tour_url);
    }

    /**
     * Get supported display devices
     *
     * @return array
     */
    public function getSupportedDevices(): array
    {
        return [
            'looking_glass' => [
                'name' => 'Looking Glass Portrait',
                'resolution' => '1536x2048',
                'viewing_angle' => '40°',
            ],
            'looking_glass_pro' => [
                'name' => 'Looking Glass Pro',
                'resolution' => '4096x4096',
                'viewing_angle' => '50°',
            ],
            'holofan' => [
                'name' => 'Holofan',
                'resolution' => '1920x1080',
                'viewing_angle' => '360°',
            ],
            'hololamp' => [
                'name' => 'Hololamp',
                'resolution' => '2560x1440',
                'viewing_angle' => '180°',
            ],
            'web_viewer' => [
                'name' => 'Web-based Holographic Viewer',
                'resolution' => 'Adaptive',
                'viewing_angle' => 'Interactive',
            ],
        ];
    }

    /**
     * Validate holographic content
     *
     * @param array $metadata
     * @return bool
     */
    public function validateContent(array $metadata): bool
    {
        $required = ['property_id', 'model_url', 'display_type'];
        
        foreach ($required as $field) {
            if (!isset($metadata[$field]) || empty($metadata[$field])) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get holographic tour metadata
     *
     * @param Property $property
     * @return array|null
     */
    public function getMetadata(Property $property): ?array
    {
        if ($property->holographic_metadata) {
            return is_array($property->holographic_metadata) 
                ? $property->holographic_metadata 
                : json_decode($property->holographic_metadata, true);
        }

        $cacheKey = "holographic_tour_{$property->id}";
        return Cache::get($cacheKey);
    }

    /**
     * Update holographic tour configuration
     *
     * @param Property $property
     * @param array $config
     * @return bool
     */
    public function updateConfiguration(Property $property, array $config): bool
    {
        try {
            $metadata = $property->holographic_metadata ?? [];
            
            if (is_string($metadata)) {
                $metadata = json_decode($metadata, true) ?? [];
            }
            
            $metadata = array_merge($metadata, $config);
            
            $property->update([
                'holographic_metadata' => $metadata,
            ]);
            
            // Clear cache
            Cache::forget("holographic_tour_{$property->id}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to update holographic configuration: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Disable holographic tour for property
     *
     * @param Property $property
     * @return bool
     */
    public function disable(Property $property): bool
    {
        try {
            $property->update([
                'holographic_enabled' => false,
            ]);
            
            Cache::forget("holographic_tour_{$property->id}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to disable holographic tour: {$e->getMessage()}");
            return false;
        }
    }
}
