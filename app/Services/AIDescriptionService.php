<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIDescriptionService
{
    protected $apiKey;
    protected $apiEndpoint;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->apiEndpoint = 'https://api.openai.com/v1/engines/davinci-codex/completions';
    }

    public function generateDescription(array $propertyData)
    {
        $prompt = $this->createPrompt($propertyData);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiEndpoint, [
            'prompt' => $prompt,
            'max_tokens' => 200,
            'temperature' => 0.7,
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['text'];
        }

        throw new \Exception('Failed to generate AI description');
    }

    protected function createPrompt(array $propertyData)
    {
        return "Generate an appealing property description for a {$propertyData['property_type']} with {$propertyData['bedrooms']} bedrooms, {$propertyData['bathrooms']} bathrooms, {$propertyData['area_sqft']} sqft, located in {$propertyData['location']}, priced at ${$propertyData['price']}:";
    }
}