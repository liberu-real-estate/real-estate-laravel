<?php

namespace App\Services;

use App\Models\PropertyFeature;
use Illuminate\Support\Facades\Cache;

class PropertyFeatureService
{
    public function getFeatures()
    {
        return Cache::remember('property_features', now()->addHours(24), function () {
            return PropertyFeature::distinct('feature_name')->pluck('feature_name');
        });
    }

    public function clearCache()
    {
        Cache::forget('property_features');
    }
}