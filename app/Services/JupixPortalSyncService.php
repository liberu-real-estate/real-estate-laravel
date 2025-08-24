<?php

namespace App\Services;

use Exception;
use App\Models\Property;
use Illuminate\Support\Facades\Log;

class JupixPortalSyncService
{
    protected $jupixApiService;

    public function __construct(JupixApiService $jupixApiService)
    {
        $this->jupixApiService = $jupixApiService;
    }

    public function syncProperties()
    {
        $jupixProperties = $this->jupixApiService->getProperties();
        $synced = 0;
        $failed = 0;

        if (!$jupixProperties) {
            Log::error('Failed to fetch properties from Jupix');
            return ['synced' => $synced, 'failed' => $failed];
        }

        foreach ($jupixProperties as $jupixProperty) {
            try {
                $property = Property::updateOrCreate(
                    ['jupix_id' => $jupixProperty['id']],
                    $this->transformJupixProperty($jupixProperty)
                );

                $property->last_synced_at = now();
                $property->save();

                $synced++;
            } catch (Exception $e) {
                Log::error('Error syncing Jupix property', [
                    'jupix_id' => $jupixProperty['id'],
                    'error' => $e->getMessage()
                ]);
                $failed++;
            }
        }

        return ['synced' => $synced, 'failed' => $failed];
    }

    protected function transformJupixProperty($jupixProperty)
    {
        // Transform Jupix property data to match our Property model
        return [
            'title' => $jupixProperty['title'],
            'description' => $jupixProperty['description'],
            'location' => $jupixProperty['address'],
            'price' => $jupixProperty['price'],
            'bedrooms' => $jupixProperty['bedrooms'],
            'bathrooms' => $jupixProperty['bathrooms'],
            'area_sqft' => $jupixProperty['area'],
            'property_type' => $jupixProperty['type'],
            'status' => $jupixProperty['status'],
            // Add more fields as needed
        ];
    }
}