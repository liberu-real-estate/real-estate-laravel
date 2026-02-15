<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\User;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user or create a default one for authorship
        $author = User::first();

        if (!$author) {
            $this->command->warn('No users found. Please create users first.');
            return;
        }

        // Create sample news articles with real estate-related content
        $newsArticles = [
            [
                'title' => 'New Property Market Trends for 2024',
                'excerpt' => 'Discover the latest trends shaping the real estate market this year, from sustainable housing to smart home technology.',
                'content' => '<p>The real estate market continues to evolve with new trends emerging in 2024. <strong>Sustainability</strong> has become a top priority for buyers, with eco-friendly features and energy-efficient homes in high demand.</p><p>Smart home technology integration is no longer a luxury but an expectation. From automated lighting to advanced security systems, modern buyers are looking for homes that offer convenience and connectivity.</p><p>Urban areas are seeing a shift towards mixed-use developments, combining residential, commercial, and recreational spaces in one location. This trend reflects changing lifestyle preferences and the desire for walkable, community-oriented neighborhoods.</p>',
                'is_featured' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Investment Opportunities in Emerging Neighborhoods',
                'excerpt' => 'Learn about the up-and-coming areas that offer great potential for property investors.',
                'content' => '<p>Smart investors are always on the lookout for emerging neighborhoods that promise growth and appreciation. Several areas have shown remarkable potential this quarter.</p><p><strong>Key factors to consider:</strong></p><ul><li>Infrastructure development plans</li><li>Access to public transportation</li><li>Local amenities and schools</li><li>Job market growth in the area</li></ul><p>Our analysis shows that neighborhoods with planned metro extensions and new commercial developments offer the best opportunities for long-term investment.</p>',
                'is_featured' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Understanding Mortgage Rates and Financing Options',
                'excerpt' => 'A comprehensive guide to navigating the current mortgage landscape and finding the best financing for your property purchase.',
                'content' => '<p>Mortgage rates have been fluctuating in response to economic conditions. Understanding these changes is crucial for making informed decisions about property financing.</p><p>Fixed-rate mortgages offer stability with consistent monthly payments, while adjustable-rate mortgages may start with lower rates but carry more risk. First-time buyers should explore government-backed loan programs that offer favorable terms.</p><p>Working with a qualified mortgage broker can help you compare options and find the best rates for your situation. Remember to factor in closing costs, insurance, and potential rate changes when budgeting for your purchase.</p>',
                'is_featured' => false,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Top 10 Home Renovations That Add Value',
                'excerpt' => 'Find out which home improvements offer the best return on investment and increase your property\'s market value.',
                'content' => '<p>Not all renovations are created equal when it comes to adding value to your home. Here are the top improvements that offer the best ROI:</p><ol><li><strong>Kitchen Remodeling</strong> - Modern kitchens are a major selling point</li><li><strong>Bathroom Upgrades</strong> - Updated bathrooms appeal to buyers</li><li><strong>Energy-Efficient Windows</strong> - Lower utility bills and better comfort</li><li><strong>Fresh Paint</strong> - Simple but effective improvement</li><li><strong>Landscaping</strong> - Curb appeal matters</li></ol><p>Focus on improvements that buyers in your area value most, and avoid over-personalizing renovations.</p>',
                'is_featured' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'The Rise of Remote Work and Its Impact on Real Estate',
                'excerpt' => 'How the shift to remote work is changing where and how people choose to live.',
                'content' => '<p>The widespread adoption of remote work has fundamentally changed real estate preferences. Workers are no longer tied to living near their offices, opening up new possibilities for location choices.</p><p>Suburban and rural areas are experiencing renewed interest as people seek more space, lower costs, and better quality of life. Home offices have become essential, with many buyers prioritizing properties that offer dedicated workspace.</p><p>This shift is also affecting commercial real estate, with office spaces being repurposed and mixed-use developments becoming more popular. The long-term implications of this trend continue to shape the market.</p>',
                'is_featured' => false,
                'published_at' => now()->subDays(14),
            ],
        ];

        foreach ($newsArticles as $article) {
            News::create(array_merge($article, [
                'author_id' => $author->id,
            ]));
        }

        // Create some additional random news articles
        News::factory()->count(10)->create([
            'author_id' => $author->id,
        ]);

        // Create a few draft articles
        News::factory()->count(3)->draft()->create([
            'author_id' => $author->id,
        ]);

        $this->command->info('News articles seeded successfully!');
    }
}
