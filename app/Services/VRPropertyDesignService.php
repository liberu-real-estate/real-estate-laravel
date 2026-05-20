<?php

namespace App\Services;

use App\Models\Property;
use App\Models\VRDesign;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\UploadedFile;

class VRPropertyDesignService
{
    /**
     * Get all available design styles.
     */
    public function getDesignStyles(): array
    {
        return config('vr-design.styles', []);
    }

    /**
     * Get all furniture categories.
     */
    public function getFurnitureCategories(): array
    {
        return config('vr-design.furniture_categories', []);
    }

    /**
     * Get all room types.
     */
    public function getRoomTypes(): array
    {
        return config('vr-design.room_types', []);
    }

    /**
     * Get supported VR devices.
     */
    public function getSupportedDevices(): array
    {
        return config('vr-design.supported_devices', []);
    }

    /**
     * Create a new VR design for a property.
     */
    public function createDesign(
        Property $property,
        User $user,
        string $name,
        array $designData,
        ?string $description = null,
        ?string $style = null,
        bool $isPublic = false
    ): VRDesign {
        $design = new VRDesign([
            'property_id' => $property->id,
            'user_id' => $user->id,
            'team_id' => $user->current_team_id,
            'name' => $name,
            'description' => $description,
            'vr_provider' => config('vr-design.provider', 'mock'),
            'design_data' => $designData,
            'style' => $style,
            'is_public' => $isPublic,
        ]);

        $design->save();

        // Generate thumbnail if mock provider
        if ($design->vr_provider === 'mock') {
            $this->generateMockThumbnail($design);
        }

        return $design;
    }

    /**
     * Update an existing VR design.
     */
    public function updateDesign(
        VRDesign $design,
        array $updates
    ): VRDesign {
        $allowedFields = [
            'name',
            'description',
            'design_data',
            'room_layout',
            'furniture_items',
            'materials',
            'lighting',
            'style',
            'is_public',
        ];

        foreach ($updates as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $design->$field = $value;
            }
        }

        $design->save();

        // Clear cache for this design
        $this->clearDesignCache($design);

        // Regenerate thumbnail if design data changed
        if (isset($updates['design_data']) || isset($updates['style'])) {
            $this->generateMockThumbnail($design);
        }

        return $design;
    }

    /**
     * Delete a VR design.
     */
    public function deleteDesign(VRDesign $design): bool
    {
        // Delete thumbnail if exists
        if ($design->thumbnail_path) {
            Storage::disk(config('vr-design.storage.disk'))->delete($design->thumbnail_path);
        }

        // Delete VR scene file if exists
        if ($design->vr_scene_url) {
            Storage::disk(config('vr-design.storage.disk'))->delete($design->vr_scene_url);
        }

        // Clear cache
        $this->clearDesignCache($design);

        return $design->delete();
    }

    /**
     * Get all designs for a property.
     */
    public function getPropertyDesigns(Property $property, bool $publicOnly = false): array
    {
        $query = VRDesign::where('property_id', $property->id)
            ->with(['user', 'team']);

        if ($publicOnly) {
            $query->public();
        }

        return $query->orderBy('created_at', 'desc')->get()->toArray();
    }

    /**
     * Get a specific design with caching.
     */
    public function getDesign(int $designId): ?VRDesign
    {
        if (!config('vr-design.cache.enabled')) {
            return VRDesign::with(['property', 'user', 'team'])->find($designId);
        }

        $cacheKey = config('vr-design.cache.prefix') . $designId;
        $cacheTtl = config('vr-design.cache.ttl');

        return Cache::remember($cacheKey, $cacheTtl, function () use ($designId) {
            return VRDesign::with(['property', 'user', 'team'])->find($designId);
        });
    }

    /**
     * Apply a furniture item to the design.
     */
    public function addFurniture(
        VRDesign $design,
        string $category,
        string $type,
        array $position,
        array $rotation = [0, 0, 0],
        array $scale = [1, 1, 1],
        array $material = []
    ): VRDesign {
        $furnitureItems = $design->furniture_items ?? [];
        
        $furnitureItems[] = [
            'id' => uniqid('furniture_'),
            'category' => $category,
            'type' => $type,
            'position' => $position,
            'rotation' => $rotation,
            'scale' => $scale,
            'material' => $material,
            'created_at' => now()->toIso8601String(),
        ];

        $design->furniture_items = $furnitureItems;
        $design->save();

        $this->clearDesignCache($design);

        return $design;
    }

    /**
     * Remove a furniture item from the design.
     */
    public function removeFurniture(VRDesign $design, string $furnitureId): VRDesign
    {
        $furnitureItems = $design->furniture_items ?? [];
        
        $design->furniture_items = array_values(
            array_filter($furnitureItems, fn($item) => $item['id'] !== $furnitureId)
        );
        
        $design->save();
        $this->clearDesignCache($design);

        return $design;
    }

    /**
     * Update room layout.
     */
    public function updateRoomLayout(
        VRDesign $design,
        array $layout
    ): VRDesign {
        $design->room_layout = $layout;
        $design->save();

        $this->clearDesignCache($design);

        return $design;
    }

    /**
     * Update materials (walls, floors, ceilings).
     */
    public function updateMaterials(
        VRDesign $design,
        array $materials
    ): VRDesign {
        $design->materials = $materials;
        $design->save();

        $this->clearDesignCache($design);

        return $design;
    }

    /**
     * Update lighting configuration.
     */
    public function updateLighting(
        VRDesign $design,
        array $lighting
    ): VRDesign {
        $design->lighting = $lighting;
        $design->save();

        $this->clearDesignCache($design);

        return $design;
    }

    /**
     * Generate VR scene URL for the design.
     */
    public function generateVRScene(VRDesign $design): string
    {
        $provider = config('vr-design.provider');

        if ($provider === 'mock') {
            return $this->generateMockVRScene($design);
        }

        // Placeholder for real VR providers
        // This would integrate with Three.js, Babylon.js, or A-Frame
        return '';
    }

    /**
     * Clone a design to create a new variation.
     */
    public function cloneDesign(
        VRDesign $originalDesign,
        User $user,
        string $newName
    ): VRDesign {
        $clonedDesign = $originalDesign->replicate();
        $clonedDesign->user_id = $user->id;
        $clonedDesign->team_id = $user->current_team_id;
        $clonedDesign->name = $newName;
        $clonedDesign->view_count = 0;
        $clonedDesign->is_template = false;
        $clonedDesign->save();

        return $clonedDesign;
    }

    /**
     * Create a template from an existing design.
     */
    public function createTemplate(VRDesign $design, string $templateName): VRDesign
    {
        $template = $design->replicate();
        $template->name = $templateName;
        $template->is_template = true;
        $template->is_public = true;
        $template->property_id = $design->property_id; // Keep reference
        $template->save();

        return $template;
    }

    /**
     * Get all available templates.
     */
    public function getTemplates(?string $style = null): array
    {
        $query = VRDesign::templates()->with(['user']);

        if ($style) {
            $query->byStyle($style);
        }

        return $query->orderBy('view_count', 'desc')->get()->toArray();
    }

    /**
     * Generate a mock thumbnail for the design.
     */
    protected function generateMockThumbnail(VRDesign $design): void
    {
        // In a real implementation, this would render the 3D scene to an image
        // For mock purposes, we'll just create a placeholder path
        $thumbnailPath = config('vr-design.storage.thumbnail_path') . '/' . $design->id . '.jpg';
        $design->thumbnail_path = $thumbnailPath;
        $design->save();
    }

    /**
     * Generate a mock VR scene for development.
     */
    protected function generateMockVRScene(VRDesign $design): string
    {
        // Return a mock VR scene URL
        return route('vr-designs.view', ['design' => $design->id]);
    }

    /**
     * Clear cached design data.
     */
    protected function clearDesignCache(VRDesign $design): void
    {
        if (config('vr-design.cache.enabled')) {
            $cacheKey = config('vr-design.cache.prefix') . $design->id;
            Cache::forget($cacheKey);
        }
    }

    /**
     * Upload and attach a thumbnail image.
     */
    public function uploadThumbnail(VRDesign $design, UploadedFile $file): VRDesign
    {
        // Delete old thumbnail if exists
        if ($design->thumbnail_path) {
            Storage::disk(config('vr-design.storage.disk'))->delete($design->thumbnail_path);
        }

        $path = $file->store(
            config('vr-design.storage.thumbnail_path'),
            config('vr-design.storage.disk')
        );

        $design->thumbnail_path = $path;
        $design->save();

        $this->clearDesignCache($design);

        return $design;
    }

    /**
     * Export design data in various formats.
     */
    public function exportDesign(VRDesign $design, string $format = 'json'): array
    {
        $data = [
            'id' => $design->id,
            'name' => $design->name,
            'description' => $design->description,
            'style' => $design->style,
            'design_data' => $design->design_data,
            'room_layout' => $design->room_layout,
            'furniture_items' => $design->furniture_items,
            'materials' => $design->materials,
            'lighting' => $design->lighting,
            'metadata' => [
                'provider' => $design->vr_provider,
                'created_at' => $design->created_at,
                'updated_at' => $design->updated_at,
            ],
        ];

        return $data;
    }
}
