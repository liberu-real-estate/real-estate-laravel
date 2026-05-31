<?php

namespace Tests\Feature;

use App\Livewire\NewsList;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivewireNewsListTest extends TestCase
{
    use RefreshDatabase;

    private User $author;

    protected function setUp(): void
    {
        parent::setUp();
        $this->author = User::factory()->create();
    }

    public function test_news_list_component_class_exists(): void
    {
        $this->assertTrue(class_exists(NewsList::class));
    }

    public function test_news_list_has_search_property(): void
    {
        $component = new NewsList();
        $this->assertSame('', $component->search);
    }

    public function test_news_list_has_featured_only_property(): void
    {
        $component = new NewsList();
        $this->assertFalse($component->featuredOnly);
    }

    public function test_news_api_returns_published_articles(): void
    {
        News::factory()->count(3)->create([
            'author_id' => $this->author->id,
            'published_at' => now()->subDay(),
        ]);
        News::factory()->create([
            'author_id' => $this->author->id,
            'published_at' => null,
        ]);

        $response = $this->getJson('/api/news');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
        $this->assertCount(3, $response->json('data'));
    }

    public function test_news_api_filters_featured(): void
    {
        News::factory()->create([
            'author_id' => $this->author->id,
            'published_at' => now()->subDay(),
            'is_featured' => true,
        ]);
        News::factory()->create([
            'author_id' => $this->author->id,
            'published_at' => now()->subDay(),
            'is_featured' => false,
        ]);

        $response = $this->getJson('/api/news/featured');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }
}
