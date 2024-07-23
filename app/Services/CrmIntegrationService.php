<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Lead;

class CrmIntegrationService
{
    protected $apiKey;
    protected $endpoint;

    public function __construct()
    {
        $this->apiKey = config('services.crm.api_key');
        $this->endpoint = config('services.crm.endpoint');
    }

    public function syncLead(Lead $lead)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->endpoint . '/leads', [
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'interest' => $lead->interest,
            'message' => $lead->message,
            'score' => $lead->score,
        ]);

        if ($response->successful()) {
            $lead->update(['crm_id' => $response->json('id')]);
        }

        return $response->successful();
    }
}