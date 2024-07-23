<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Lead;
use App\Models\Activity;
use Illuminate\Support\Facades\Log;

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
        try {
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
                return true;
            } else {
                Log::error('CRM sync failed for lead ' . $lead->id . ': ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('CRM sync error for lead ' . $lead->id . ': ' . $e->getMessage());
            return false;
        }
    }

    public function syncActivity(Activity $activity)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->endpoint . '/activities', [
                'lead_id' => $activity->lead->crm_id,
                'type' => $activity->type,
                'description' => $activity->description,
                'date' => $activity->created_at->toDateTimeString(),
            ]);

            if ($response->successful()) {
                $activity->update(['crm_id' => $response->json('id')]);
                return true;
            } else {
                Log::error('CRM sync failed for activity ' . $activity->id . ': ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('CRM sync error for activity ' . $activity->id . ': ' . $e->getMessage());
            return false;
        }
    }
}