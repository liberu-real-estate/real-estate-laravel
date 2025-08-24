<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CreditReportService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.credit_report.api_key');
        $this->apiUrl = config('services.credit_report.api_url');
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
                return $result['credit_score'];
            } else {
                Log::error('Credit report API error: ' . $response->body());
                return 'error';
            }
        } catch (Exception $e) {
            Log::error('Credit report error: ' . $e->getMessage());
            return 'error';
        }
    }
}