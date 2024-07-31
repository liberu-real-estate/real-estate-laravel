<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LetsSafeScreeningService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.lets_safe.api_key');
        $this->apiUrl = config('services.lets_safe.api_url');
    }

    public function screenTenant($tenantId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post($this->apiUrl . '/screen', [
                'tenant_id' => $tenantId,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return [
                    'credit_score' => $result['credit_score'] ?? null,
                    'background_check' => $result['background_check'] ?? null,
                    'rental_history' => $result['rental_history'] ?? null,
                ];
            } else {
                Log::error('Let\'s Safe API error: ' . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Let\'s Safe screening error: ' . $e->getMessage());
            return null;
        }
    }
}