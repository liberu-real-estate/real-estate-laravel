<?php

namespace App\Services;

use InvalidArgumentException;

class AllAgentsService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct(string $apiKey = '', string $baseUrl = 'https://api.allagents.co.uk/v1')
    {
        $this->apiKey = $apiKey ?: config('services.allagents.api_key', '');
        $this->baseUrl = $baseUrl;
    }

    /**
     * Get reviews for a branch/office.
     *
     * @param  string  $branchId  AllAgents branch identifier
     * @param  int  $limit
     * @param  int  $page
     * @return array
     */
    public function getBranchReviews(string $branchId, int $limit = 10, int $page = 1): array
    {
        if (empty($branchId)) {
            throw new InvalidArgumentException('Branch ID cannot be empty.');
        }

        if ($limit < 1 || $limit > 100) {
            throw new InvalidArgumentException('Limit must be between 1 and 100.');
        }

        return $this->makeApiRequest("/reviews/branch/{$branchId}", [
            'limit' => $limit,
            'page' => $page,
        ]);
    }

    /**
     * Get the overall rating for a branch.
     *
     * @param  string  $branchId
     * @return array
     */
    public function getBranchRating(string $branchId): array
    {
        if (empty($branchId)) {
            throw new InvalidArgumentException('Branch ID cannot be empty.');
        }

        return $this->makeApiRequest("/ratings/branch/{$branchId}");
    }

    /**
     * Get reviews for a specific agent.
     *
     * @param  string  $agentId  AllAgents agent identifier
     * @param  int  $limit
     * @return array
     */
    public function getAgentReviews(string $agentId, int $limit = 10): array
    {
        if (empty($agentId)) {
            throw new InvalidArgumentException('Agent ID cannot be empty.');
        }

        return $this->makeApiRequest("/reviews/agent/{$agentId}", ['limit' => $limit]);
    }

    /**
     * Get the embed HTML for displaying AllAgents reviews on a website.
     *
     * @param  string  $branchId
     * @param  array  $options
     * @return string  HTML snippet
     */
    public function getEmbedHtml(string $branchId, array $options = []): string
    {
        $width = $options['width'] ?? '100%';
        $theme = $options['theme'] ?? 'light';
        $limit = $options['limit'] ?? 5;

        return <<<HTML
        <div class="allagents-reviews-widget" 
             data-branch-id="{$branchId}" 
             data-theme="{$theme}"
             data-limit="{$limit}"
             style="width:{$width}">
        </div>
        <script>
            (function() {
                var script = document.createElement('script');
                script.src = 'https://widgets.allagents.co.uk/reviews.js';
                script.async = true;
                document.head.appendChild(script);
            })();
        </script>
        HTML;
    }

    /**
     * Format reviews for display.
     *
     * @param  array  $reviews
     * @return array
     */
    public function formatReviewsForDisplay(array $reviews): array
    {
        $items = $reviews['data'] ?? $reviews;

        return array_map(function (array $review) {
            return [
                'id' => $review['id'] ?? null,
                'reviewer_name' => $review['reviewer_name'] ?? 'Anonymous',
                'reviewer_type' => $review['reviewer_type'] ?? null,
                'rating' => $review['rating'] ?? null,
                'title' => $review['title'] ?? null,
                'body' => $review['body'] ?? null,
                'date' => $review['date'] ?? null,
                'reply' => $review['reply'] ?? null,
                'verified' => $review['verified'] ?? false,
                'stars' => $this->ratingToStars($review['rating'] ?? 0),
            ];
        }, is_array($items) ? $items : []);
    }

    /**
     * Get the aggregate rating summary.
     *
     * @param  array  $ratingData
     * @return array
     */
    public function formatRatingSummary(array $ratingData): array
    {
        return [
            'average_rating' => $ratingData['average_rating'] ?? null,
            'total_reviews' => $ratingData['total_reviews'] ?? 0,
            'rating_breakdown' => [
                5 => $ratingData['five_star'] ?? 0,
                4 => $ratingData['four_star'] ?? 0,
                3 => $ratingData['three_star'] ?? 0,
                2 => $ratingData['two_star'] ?? 0,
                1 => $ratingData['one_star'] ?? 0,
            ],
            'recommendation_percentage' => $ratingData['recommendation_percentage'] ?? null,
        ];
    }

    private function ratingToStars(int $rating): string
    {
        $filled = str_repeat('★', max(0, min(5, $rating)));
        $empty = str_repeat('☆', max(0, 5 - $rating));

        return $filled . $empty;
    }

    private function makeApiRequest(string $endpoint, array $params = []): array
    {
        if (empty($this->apiKey)) {
            return ['error' => 'AllAgents API key not configured', 'data' => []];
        }

        $url = $this->baseUrl . $endpoint . '?' . http_build_query(array_merge(['api_key' => $this->apiKey], $params));

        $context = stream_context_create(['http' => [
            'timeout' => 10,
            'header' => 'Accept: application/json',
        ]]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return ['error' => 'Failed to connect to AllAgents API', 'data' => []];
        }

        $decoded = json_decode($response, true);

        return is_array($decoded) ? $decoded : ['data' => []];
    }
}
