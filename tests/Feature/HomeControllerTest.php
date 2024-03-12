<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Property;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexMethodReturnsFeaturedProperties()
    {
        Property::factory()->count(5)->create(['is_featured' => true]);
        Property::factory()->count(3)->create(['is_featured' => false]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('featuredProperties', function ($viewFeaturedProperties) {
            return $viewFeaturedProperties->count() == 5 && $viewFeaturedProperties->every(function ($property) {
                return $property->is_featured;
            });
        });
    }

    public function testIndexMethodView()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }
}
