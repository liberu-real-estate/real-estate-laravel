<?php

namespace App\Services;

use App\Models\ComponentSettings;
use Illuminate\Support\Facades\Cache;

class ComponentSettingsService
{
    private $cacheKey = 'component_settings';
    private $cacheDuration = 3600; // 1 hour

    public function getAllSettings()
    {
        return Cache::remember($this->cacheKey, $this->cacheDuration, function () {
            try {
                if (Schema::hasTable('component_settings')) {
                    return ComponentSettings::all()->keyBy('component_name')->toArray();
                }
            } catch (\Exception $e) {
                // Log the error if needed
                // \Log::error('Error accessing component_settings table: ' . $e->getMessage());
            }
            return [];

        });
    }

    public function isEnabled($componentName)
    {
        $settings = $this->getAllSettings();
        return isset($settings[$componentName]) ? $settings[$componentName]['is_enabled'] : true;
    }

    public function clear()
    {
        Cache::forget($this->cacheKey);
    }
}
