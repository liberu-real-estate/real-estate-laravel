<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BackgroundCheckService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.background_check.api_key');
        $this->apiUrl = config('services.background_check.api_url');
    }

    public function check($tenantId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post($this->apiUrl, [
                'tenant_id' => $tenantId,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return $result['status'];
            } else {
                Log::error('Background check API error: ' . $response->body());
                return 'error';
            }
        } catch (Exception $e) {
            Log::error('Background check error: ' . $e->getMessage());
            return 'error';
        }
    }
}