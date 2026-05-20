<?php

namespace App\Services;

use InvalidArgumentException;

class LocratingService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct(string $apiKey = '', string $baseUrl = 'https://api.locrating.com/v1')
    {
        $this->apiKey = $apiKey ?: config('services.locrating.api_key', '');
        $this->baseUrl = $baseUrl;
    }

    /**
     * Get schools near a property location.
     *
     * @param  float  $latitude
     * @param  float  $longitude
     * @param  float  $radiusMiles
     * @param  string|null  $phase  'primary', 'secondary', 'all'
     * @return array
     */
    public function getSchoolsNearLocation(
        float $latitude,
        float $longitude,
        float $radiusMiles = 1.0,
        ?string $phase = null
    ): array {
        if ($radiusMiles <= 0 || $radiusMiles > 25) {
            throw new InvalidArgumentException('Radius must be greater than 0 and no more than 25 miles.');
        }

        $params = [
            'lat' => $latitude,
            'lon' => $longitude,
            'radius' => $radiusMiles,
            'api_key' => $this->apiKey,
        ];

        if ($phase && in_array($phase, ['primary', 'secondary'])) {
            $params['phase'] = $phase;
        }

        return $this->makeApiRequest('/schools', $params);
    }

    /**
     * Get catchment area information for a specific school.
     *
     * @param  string  $schoolId
     * @return array
     */
    public function getSchoolCatchmentArea(string $schoolId): array
    {
        if (empty($schoolId)) {
            throw new InvalidArgumentException('School ID cannot be empty.');
        }

        return $this->makeApiRequest("/schools/{$schoolId}/catchment");
    }

    /**
     * Check if a property falls within a school's catchment area.
     *
     * @param  float  $latitude
     * @param  float  $longitude
     * @param  string  $schoolId
     * @return array
     */
    public function isInCatchmentArea(float $latitude, float $longitude, string $schoolId): array
    {
        return $this->makeApiRequest("/schools/{$schoolId}/catchment/check", [
            'lat' => $latitude,
            'lon' => $longitude,
            'api_key' => $this->apiKey,
        ]);
    }

    /**
     * Get Ofsted/inspection rating for a school.
     *
     * @param  string  $schoolId
     * @return array
     */
    public function getSchoolOfstedRating(string $schoolId): array
    {
        if (empty($schoolId)) {
            throw new InvalidArgumentException('School ID cannot be empty.');
        }

        return $this->makeApiRequest("/schools/{$schoolId}/inspection");
    }

    /**
     * Format school data for display in property listings.
     *
     * @param  array  $schools
     * @return array
     */
    public function formatSchoolsForDisplay(array $schools): array
    {
        return array_map(function (array $school) {
            return [
                'id' => $school['id'] ?? null,
                'name' => $school['name'] ?? 'Unknown School',
                'phase' => $school['phase'] ?? null,
                'type' => $school['type'] ?? null,
                'ofsted_rating' => $school['ofsted_rating'] ?? null,
                'ofsted_label' => $this->getOfstedLabel($school['ofsted_rating'] ?? null),
                'distance_miles' => isset($school['distance']) ? round($school['distance'], 2) : null,
                'is_in_catchment' => $school['is_in_catchment'] ?? null,
                'address' => $school['address'] ?? null,
                'postcode' => $school['postcode'] ?? null,
            ];
        }, $schools);
    }

    private function getOfstedLabel(?int $rating): string
    {
        return match ($rating) {
            1 => 'Outstanding',
            2 => 'Good',
            3 => 'Requires Improvement',
            4 => 'Inadequate',
            default => 'Not yet inspected',
        };
    }

    private function makeApiRequest(string $endpoint, array $params = []): array
    {
        if (empty($this->apiKey)) {
            return ['error' => 'Locrating API key not configured', 'data' => []];
        }

        $url = $this->baseUrl . $endpoint . '?' . http_build_query(array_merge(['api_key' => $this->apiKey], $params));

        $context = stream_context_create(['http' => [
            'timeout' => 10,
            'header' => 'Accept: application/json',
        ]]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return ['error' => 'Failed to connect to Locrating API', 'data' => []];
        }

        $decoded = json_decode($response, true);

        return is_array($decoded) ? $decoded : ['data' => []];
    }
}
