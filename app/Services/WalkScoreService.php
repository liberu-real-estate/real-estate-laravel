<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WalkScoreService
{
    protected $baseUri;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUri = config('services.walkscore.base_uri');
        $this->apiKey = config('services.walkscore.api_key');
    }

    /**
     * Fetch walkability score for a given address
     *
     * @param string $address
     * @param float $latitude
     * @param float $longitude
     * @return array|null
     */
    public function getWalkScore($address, $latitude, $longitude)
    {
        try {
            // If API key is not configured, return mock data for development
            if (empty($this->apiKey)) {
                return $this->getMockWalkScore($latitude, $longitude);
            }

            $response = Http::get("{$this->baseUri}/score", [
                'format' => 'json',
                'address' => $address,
                'lat' => $latitude,
                'lon' => $longitude,
                'wsapikey' => $this->apiKey,
            ]);

            if ($response->failed()) {
                Log::warning('Walk Score API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return $this->getMockWalkScore($latitude, $longitude);
            }

            $data = $response->json();

            return [
                'walk_score' => $this->validateScore($data['walkscore'] ?? null),
                'walk_description' => $data['description'] ?? null,
                'transit_score' => $this->validateScore($data['transit']['score'] ?? null),
                'transit_description' => $data['transit']['description'] ?? null,
                'bike_score' => $this->validateScore($data['bike']['score'] ?? null),
                'bike_description' => $data['bike']['description'] ?? null,
            ];
        } catch (Exception $e) {
            Log::error('Walk Score fetch failed: ' . $e->getMessage());
            return $this->getMockWalkScore($latitude, $longitude);
        }
    }

    /**
     * Generate mock walkability scores for development/testing
     *
     * @param float $latitude
     * @param float $longitude
     * @return array
     */
    protected function getMockWalkScore($latitude, $longitude)
    {
        // Generate deterministic scores based on location
        $seed = abs(($latitude + $longitude) * 100);
        $walkScore = (int) (($seed % 60) + 30); // Score between 30-90
        $transitScore = (int) (($seed % 50) + 40); // Score between 40-90
        $bikeScore = (int) (($seed % 55) + 35); // Score between 35-90

        return [
            'walk_score' => $walkScore,
            'walk_description' => $this->getWalkScoreDescription($walkScore),
            'transit_score' => $transitScore,
            'transit_description' => $this->getTransitScoreDescription($transitScore),
            'bike_score' => $bikeScore,
            'bike_description' => $this->getBikeScoreDescription($bikeScore),
        ];
    }

    /**
     * Get description for walk score
     *
     * @param int $score
     * @return string
     */
    protected function getWalkScoreDescription($score)
    {
        if ($score >= 90) return 'Walker\'s Paradise';
        if ($score >= 70) return 'Very Walkable';
        if ($score >= 50) return 'Somewhat Walkable';
        if ($score >= 25) return 'Car-Dependent';
        return 'Very Car-Dependent';
    }

    /**
     * Get description for transit score
     *
     * @param int $score
     * @return string
     */
    protected function getTransitScoreDescription($score)
    {
        if ($score >= 90) return 'Rider\'s Paradise';
        if ($score >= 70) return 'Excellent Transit';
        if ($score >= 50) return 'Good Transit';
        if ($score >= 25) return 'Some Transit';
        return 'Minimal Transit';
    }

    /**
     * Get description for bike score
     *
     * @param int $score
     * @return string
     */
    protected function getBikeScoreDescription($score)
    {
        if ($score >= 90) return 'Biker\'s Paradise';
        if ($score >= 70) return 'Very Bikeable';
        if ($score >= 50) return 'Bikeable';
        return 'Somewhat Bikeable';
    }

    /**
     * Validate and clamp score to 0-100 range
     *
     * @param mixed $score
     * @return int|null
     */
    protected function validateScore($score)
    {
        if ($score === null) {
            return null;
        }

        $score = (int) $score;
        return max(0, min(100, $score));
    }
}
