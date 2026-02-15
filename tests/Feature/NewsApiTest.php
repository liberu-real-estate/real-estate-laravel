<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user for authorship
        $this->user = User::factory()->create();
    }

    public function test_can_fetch_published_news_list()
    {
        // Create published news
        News::factory()->count(3)->create([
            'author_id' => $this->user->id,
            'published_at' => now()->subDay(),
        ]);

        // Create draft news (should not appear)
        News::factory()->create([
            'author_id' => $this->user->id,
            'published_at' => null,
        ]);

        $response = $this->getJson('/api/news');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'slug',
                        'excerpt',
                        'content',
                        'is_featured',
                        'published_at',
                        'author' => [
                            'id',
                            'name',
                        ],
                    ],
                ],
                'meta' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ],
            ])
            ->assertJson([
                'success' => true,
            ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_fetch_latest_news()
    {
        News::factory()->count(10)->create([
            'author_id' => $this->user->id,
            'published_at' => now()->subDays(rand(1, 30)),
        ]);

        $response = $this->getJson('/api/news/latest?limit=5');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);

        $this->assertCount(5, $response->json('data'));
    }

    public function test_can_fetch_featured_news()
    {
        // Create featured news
        News::factory()->count(3)->create([
            'author_id' => $this->user->id,
            'published_at' => now()->subDay(),
            'is_featured' => true,
        ]);

        // Create non-featured news
        News::factory()->count(5)->create([
            'author_id' => $this->user->id,
            'published_at' => now()->subDay(),
            'is_featured' => false,
        ]);

        $response = $this->getJson('/api/news/featured');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_fetch_single_news_by_slug()
    {
        $news = News::factory()->create([
            'author_id' => $this->user->id,
            'published_at' => now()->subDay(),
            'slug' => 'test-news-article',
        ]);

        $response = $this->getJson('/api/news/test-news-article');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'slug' => 'test-news-article',
                    'title' => $news->title,
                ],
            ]);
    }

    public function test_cannot_fetch_draft_news_by_slug()
    {
        News::factory()->create([
            'author_id' => $this->user->id,
            'published_at' => null,
            'slug' => 'draft-article',
        ]);

        $response = $this->getJson('/api/news/draft-article');

        $response->assertStatus(404);
    }

    public function test_cannot_fetch_future_scheduled_news()
    {
        News::factory()->create([
            'author_id' => $this->user->id,
            'published_at' => now()->addDay(),
        ]);

        $response = $this->getJson('/api/news');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
    }

    public function test_pagination_works_correctly()
    {
        News::factory()->count(25)->create([
            'author_id' => $this->user->id,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->getJson('/api/news?per_page=10&page=1');

        $response->assertStatus(200)
            ->assertJsonPath('meta.per_page', 10)
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.total', 25);

        $this->assertCount(10, $response->json('data'));
    }
}
