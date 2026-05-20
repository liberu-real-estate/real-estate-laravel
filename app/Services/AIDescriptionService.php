<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class AIDescriptionService
{
    protected $apiKey;
    protected $apiEndpoint;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->apiEndpoint = 'https://api.openai.com/v1/chat/completions';
    }

    public function generateDescription(array $propertyData, string $tone = 'professional')
    {
        $prompt = $this->createPrompt($propertyData, $tone);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiEndpoint, [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a professional real estate agent writing property descriptions.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => 300,
            'temperature' => 0.7,
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'];
        }

        throw new Exception('Failed to generate AI description');
    }

    protected function createPrompt(array $propertyData, string $tone)
    {
        $toneInstruction = $this->getToneInstruction($tone);
        return "Generate an appealing property description for a {$propertyData['property_type']} with {$propertyData['bedrooms']} bedrooms, {$propertyData['bathrooms']} bathrooms, {$propertyData['area_sqft']} sqft, located in {$propertyData['location']}, priced at ${$propertyData['price']}. {$toneInstruction}";
    }

    protected function getToneInstruction(string $tone)
    {
        switch ($tone) {
            case 'casual':
                return "Use a casual and friendly tone.";
            case 'luxury':
                return "Use an upscale and sophisticated tone, emphasizing luxury features.";
            case 'professional':
            default:
                return "Use a professional and informative tone.";
        }
    }
}