<?php

namespace App\Services;

use App\Models\Property;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class AIAssistantService
{
    protected $apiKey;
    protected $apiEndpoint;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->apiEndpoint = 'https://api.openai.com/v1/engines/davinci-codex/completions';
    }

    public function generateResponse($input, $context)
    {
        $prompt = $this->createPrompt($input, $context);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiEndpoint, [
            'prompt' => $prompt,
            'max_tokens' => 150,
            'temperature' => 0.7,
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['text'];
        }

        throw new \Exception('Failed to generate AI response');
    }

    protected function createPrompt($input, $context)
    {
        return "As an AI assistant for property management, respond to the following input based on the given context:\n\nContext: {$context}\n\nInput: {$input}\n\nResponse:";
    }

    public function scheduleMaintenance(MaintenanceRequest $request)
    {
        // Logic to schedule maintenance
        // This is a placeholder and should be implemented based on your specific requirements
        $request->update(['status' => 'scheduled']);
        return "Maintenance scheduled for " . $request->property->address . " on " . now()->addDays(3)->toDateString();
    }

    public function generateFinancialReport(Property $property)
    {
        // Logic to generate financial report
        // This is a placeholder and should be implemented based on your specific requirements
        $income = $property->leases()->sum('rent_amount');
        $expenses = $property->maintenanceRequests()->sum('cost');
        $profit = $income - $expenses;

        return "Financial Report for {$property->address}:\nIncome: ${$income}\nExpenses: ${$expenses}\nProfit: ${$profit}";
    }

    public function generateTenantCommunication(User $tenant, $subject)
    {
        // Logic to generate tenant communication
        // This is a placeholder and should be implemented based on your specific requirements
        $context = "Tenant: {$tenant->name}, Property: {$tenant->property->address}, Subject: {$subject}";
        $input = "Generate a polite and professional message to the tenant regarding the subject.";

        return $this->generateResponse($input, $context);
    }
}