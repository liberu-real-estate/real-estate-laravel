<?php

namespace Tests\Unit;

use App\Models\News;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_news()
    {
        $user = User::factory()->create();
        
        $newsData = [
            'title' => 'Test News Article',
            'slug' => 'test-news-article',
            'excerpt' => 'This is a test excerpt',
            'content' => 'This is the full content of the test article.',
            'is_featured' => false,
            'published_at' => now(),
            'author_id' => $user->id,
        ];

        $news = News::create($newsData);

        $this->assertInstanceOf(News::class, $news);
        $this->assertDatabaseHas('news', [
            'title' => 'Test News Article',
            'slug' => 'test-news-article',
        ]);
    }

    public function test_news_published_scope()
    {
        $user = User::factory()->create();
        
        // Create published news
        News::factory()->create([
            'author_id' => $user->id,
            'published_at' => now()->subDay(),
        ]);

        // Create draft news
        News::factory()->create([
            'author_id' => $user->id,
            'published_at' => null,
        ]);

        // Create future scheduled news
        News::factory()->create([
            'author_id' => $user->id,
            'published_at' => now()->addDay(),
        ]);

        $publishedNews = News::published()->get();
        
        $this->assertEquals(1, $publishedNews->count());
    }

    public function test_news_featured_scope()
    {
        $user = User::factory()->create();
        
        News::factory()->count(3)->create([
            'author_id' => $user->id,
            'is_featured' => true,
            'published_at' => now(),
        ]);

        News::factory()->count(2)->create([
            'author_id' => $user->id,
            'is_featured' => false,
            'published_at' => now(),
        ]);

        $featuredNews = News::featured()->get();
        
        $this->assertEquals(3, $featuredNews->count());
    }

    public function test_news_author_relationship()
    {
        $user = User::factory()->create();
        
        $news = News::factory()->create([
            'author_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $news->author);
        $this->assertEquals($user->id, $news->author->id);
    }

    public function test_slug_auto_generation()
    {
        $user = User::factory()->create();
        
        $news = News::create([
            'title' => 'My Test Article Title',
            'content' => 'Test content',
            'author_id' => $user->id,
        ]);

        $this->assertEquals('my-test-article-title', $news->slug);
    }

    public function test_route_key_is_slug()
    {
        $news = new News();
        
        $this->assertEquals('slug', $news->getRouteKeyName());
    }
}
