<?php

namespace App\Services;

use App\Models\EnergyConsumption;
use Illuminate\Support\Facades\Http;

class UtilityProviderService
{
    public function fetchLatestUsage(EnergyConsumption $energyConsumption)
    {
        // Simulated API call to utility provider
        $response = Http::get('https://api.utilityprovider.com/usage', [
            'property_id' => $energyConsumption->property_id,
            'date' => $energyConsumption->consumption_date->format('Y-m-d'),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $energyConsumption->update([
                'electricity_usage' => $data['electricity_usage'],
                'gas_usage' => $data['gas_usage'],
                'water_usage' => $data['water_usage'],
                'total_cost' => $data['total_cost'],
            ]);

            return true;
        }

        return false;
    }
}