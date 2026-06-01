<?php

namespace Tests\Unit;

use App\Services\AIDescriptionService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AIDescriptionServiceTest extends TestCase
{
    private AIDescriptionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AIDescriptionService();
    }

    public function test_generates_description_with_successful_api_response(): void
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    ['message' => ['content' => 'Beautiful 3-bedroom house in London with modern amenities.']],
                ],
            ], 200),
        ]);

        $propertyData = [
            'property_type' => 'House',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'area_sqft' => 1500,
            'location' => 'London',
            'price' => 350000,
        ];

        $description = $this->service->generateDescription($propertyData, 'professional');

        $this->assertIsString($description);
        $this->assertNotEmpty($description);
    }

    public function test_throws_exception_on_api_failure(): void
    {
        Http::fake([
            'api.openai.com/*' => Http::response([], 500),
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to generate AI description');

        $this->service->generateDescription([
            'property_type' => 'House',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'area_sqft' => 1500,
            'location' => 'London',
            'price' => 350000,
        ]);
    }

    public function test_generates_description_with_luxury_tone(): void
    {
        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    ['message' => ['content' => 'Exquisite penthouse with panoramic city views.']],
                ],
            ], 200),
        ]);

        $propertyData = [
            'property_type' => 'Penthouse',
            'bedrooms' => 4,
            'bathrooms' => 3,
            'area_sqft' => 3000,
            'location' => 'Mayfair',
            'price' => 5000000,
        ];

        $description = $this->service->generateDescription($propertyData, 'luxury');

        $this->assertIsString($description);
        $this->assertNotEmpty($description);
    }
}
