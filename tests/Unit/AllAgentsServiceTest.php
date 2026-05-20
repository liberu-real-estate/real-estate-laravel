<?php

namespace Tests\Unit;

use App\Services\AllAgentsService;
use Tests\TestCase;

class AllAgentsServiceTest extends TestCase
{
    private AllAgentsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AllAgentsService('test-api-key');
    }

    public function test_throws_exception_for_empty_branch_id()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Branch ID cannot be empty');

        $this->service->getBranchReviews('');
    }

    public function test_throws_exception_for_invalid_limit()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Limit must be between 1 and 100');

        $this->service->getBranchReviews('branch-123', 0);
    }

    public function test_throws_exception_for_limit_too_high()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Limit must be between 1 and 100');

        $this->service->getBranchReviews('branch-123', 101);
    }

    public function test_throws_exception_for_empty_agent_id()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Agent ID cannot be empty');

        $this->service->getAgentReviews('');
    }

    public function test_generates_embed_html()
    {
        $html = $this->service->getEmbedHtml('branch-123');

        $this->assertStringContainsString('branch-123', $html);
        $this->assertStringContainsString('allagents', $html);
        $this->assertStringContainsString('<script>', $html);
    }

    public function test_generates_embed_html_with_custom_options()
    {
        $html = $this->service->getEmbedHtml('branch-123', [
            'theme' => 'dark',
            'limit' => 10,
            'width' => '80%',
        ]);

        $this->assertStringContainsString('dark', $html);
        $this->assertStringContainsString('10', $html);
        $this->assertStringContainsString('80%', $html);
    }

    public function test_formats_reviews_for_display()
    {
        $rawReviews = [
            [
                'id' => 1,
                'reviewer_name' => 'John Doe',
                'reviewer_type' => 'buyer',
                'rating' => 5,
                'title' => 'Excellent service',
                'body' => 'Really happy with the service.',
                'date' => '2024-01-15',
                'verified' => true,
            ],
            [
                'id' => 2,
                'reviewer_name' => 'Jane Smith',
                'rating' => 3,
                'body' => 'Average experience.',
            ],
        ];

        $formatted = $this->service->formatReviewsForDisplay($rawReviews);

        $this->assertCount(2, $formatted);
        $this->assertEquals('John Doe', $formatted[0]['reviewer_name']);
        $this->assertEquals('★★★★★', $formatted[0]['stars']);
        $this->assertEquals('★★★☆☆', $formatted[1]['stars']);
        $this->assertTrue($formatted[0]['verified']);
    }

    public function test_formats_rating_summary()
    {
        $ratingData = [
            'average_rating' => 4.2,
            'total_reviews' => 150,
            'five_star' => 80,
            'four_star' => 40,
            'three_star' => 20,
            'two_star' => 5,
            'one_star' => 5,
            'recommendation_percentage' => 88,
        ];

        $summary = $this->service->formatRatingSummary($ratingData);

        $this->assertEquals(4.2, $summary['average_rating']);
        $this->assertEquals(150, $summary['total_reviews']);
        $this->assertEquals(80, $summary['rating_breakdown'][5]);
        $this->assertEquals(88, $summary['recommendation_percentage']);
    }

    public function test_returns_error_when_api_key_not_configured()
    {
        $service = new AllAgentsService('');

        $result = $service->getBranchReviews('branch-123');

        $this->assertArrayHasKey('error', $result);
        $this->assertStringContainsString('API key not configured', $result['error']);
    }
}
