<?php

namespace Tests\Unit;

use App\Services\PropertyBrochureService;
use App\Models\Property;
use App\Models\Team;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PropertyBrochureServiceTest extends TestCase
{
    use RefreshDatabase;

    private PropertyBrochureService $service;
    private Property $property;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PropertyBrochureService();

        $team = Team::create(['name' => 'Test Team', 'user_id' => 1, 'personal_team' => false]);
        $user = User::factory()->create(['current_team_id' => $team->id]);
        $this->property = Property::factory()->create([
            'title' => 'Beautiful Family Home',
            'price' => 450000,
            'bedrooms' => 4,
            'bathrooms' => 2,
            'area_sqft' => 1800,
            'property_type' => 'house',
            'energy_rating' => 'B',
            'energy_score' => 82,
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);
    }

    public function test_generates_brochure_data()
    {
        $data = $this->service->generateBrochureData($this->property);

        $this->assertArrayHasKey('property', $data);
        $this->assertArrayHasKey('options', $data);
        $this->assertArrayHasKey('generated_at', $data);
        $this->assertEquals('Beautiful Family Home', $data['property']['title']);
        $this->assertEquals('£450,000', $data['property']['formatted_price']);
        $this->assertEquals(4, $data['property']['bedrooms']);
    }

    public function test_generates_html_brochure()
    {
        $html = $this->service->generateHtmlBrochure($this->property);

        $this->assertStringContainsString('Beautiful Family Home', $html);
        $this->assertStringContainsString('£450,000', $html);
        $this->assertStringContainsString('4', $html);
        $this->assertStringContainsString('<!DOCTYPE html>', $html);
    }

    public function test_generates_window_card()
    {
        $html = $this->service->generateWindowCard($this->property);

        $this->assertStringContainsString('Beautiful Family Home', $html);
        $this->assertStringContainsString('£450,000', $html);
        $this->assertStringContainsString('<!DOCTYPE html>', $html);
        $this->assertStringContainsString('card', $html);
    }

    public function test_includes_epc_in_brochure_by_default()
    {
        $html = $this->service->generateHtmlBrochure($this->property);

        $this->assertStringContainsString('EPC Rating', $html);
        $this->assertStringContainsString('B', $html);
    }

    public function test_excludes_epc_when_option_is_false()
    {
        $html = $this->service->generateHtmlBrochure($this->property, ['include_epc' => false]);

        $this->assertStringNotContainsString('EPC Rating', $html);
    }

    public function test_uses_standard_template_by_default()
    {
        $data = $this->service->generateBrochureData($this->property);

        $this->assertEquals('standard', $data['template']);
    }

    public function test_uses_custom_template()
    {
        $data = $this->service->generateBrochureData($this->property, ['template' => 'premium']);

        $this->assertEquals('premium', $data['template']);
    }
}
