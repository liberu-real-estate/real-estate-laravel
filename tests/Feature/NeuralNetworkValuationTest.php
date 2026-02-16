<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\PropertyValuation;
use App\Models\User;
use App\Models\Team;
use App\Services\NeuralNetworkValuationService;
use App\Services\PropertyValuationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NeuralNetworkValuationTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $team;
    private $property;
    private $nnService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a team
        $this->team = Team::factory()->create();

        // Create a user and assign to team
        $this->user = User::factory()->create([
            'current_team_id' => $this->team->id,
        ]);
        
        $this->user->teams()->attach($this->team);

        // Create a test property
        $this->property = Property::factory()->create([
            'title' => 'Test Property for Valuation',
            'location' => 'London, UK',
            'price' => 500000,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'area_sqft' => 1500,
            'year_built' => 2010,
            'property_type' => 'detached',
            'status' => 'for_sale',
            'is_featured' => false,
            'latitude' => 51.5074,
            'longitude' => -0.1278,
        ]);

        // Initialize the neural network service
        $propertyValuationService = app(PropertyValuationService::class);
        $this->nnService = new NeuralNetworkValuationService($propertyValuationService);
    }

    public function test_can_generate_valuation(): void
    {
        $result = $this->nnService->generateValuation($this->property);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('estimated_value', $result);
        $this->assertArrayHasKey('confidence_level', $result);
        $this->assertArrayHasKey('method', $result);
        $this->assertArrayHasKey('model_version', $result);
        $this->assertEquals('neural_network', $result['method']);
        $this->assertGreaterThan(0, $result['estimated_value']);
        $this->assertGreaterThanOrEqual(0, $result['confidence_level']);
        $this->assertLessThanOrEqual(100, $result['confidence_level']);
    }

    public function test_can_create_valuation_record(): void
    {
        $valuation = $this->nnService->createValuation(
            $this->property,
            $this->user->id,
            $this->team->id
        );

        $this->assertInstanceOf(PropertyValuation::class, $valuation);
        $this->assertEquals($this->property->id, $valuation->property_id);
        $this->assertEquals('neural_network', $valuation->valuation_type);
        $this->assertNotNull($valuation->estimated_value);
        $this->assertNotNull($valuation->confidence_level);
        $this->assertEquals('active', $valuation->status);
    }

    public function test_valuation_includes_feature_importance(): void
    {
        $result = $this->nnService->generateValuation($this->property);

        $this->assertArrayHasKey('feature_importance', $result);
        $this->assertIsArray($result['feature_importance']);
        $this->assertNotEmpty($result['feature_importance']);
    }

    public function test_valuation_includes_price_range(): void
    {
        $result = $this->nnService->generateValuation($this->property);

        $this->assertArrayHasKey('price_range', $result);
        $this->assertArrayHasKey('min', $result['price_range']);
        $this->assertArrayHasKey('max', $result['price_range']);
        $this->assertLessThan($result['price_range']['max'], $result['price_range']['min']);
    }

    public function test_valuation_includes_market_trend(): void
    {
        $result = $this->nnService->generateValuation($this->property);

        $this->assertArrayHasKey('market_trend', $result);
        $this->assertContains($result['market_trend'], ['rising', 'stable', 'declining', 'volatile']);
    }

    public function test_valuation_includes_prediction_factors(): void
    {
        $result = $this->nnService->generateValuation($this->property);

        $this->assertArrayHasKey('prediction_factors', $result);
        $this->assertIsArray($result['prediction_factors']);
    }

    public function test_can_get_detailed_report(): void
    {
        $report = $this->nnService->getDetailedReport($this->property);

        $this->assertIsArray($report);
        $this->assertArrayHasKey('property', $report);
        $this->assertArrayHasKey('valuation', $report);
        $this->assertArrayHasKey('comparables', $report);
        $this->assertArrayHasKey('report_date', $report);
        $this->assertArrayHasKey('model_version', $report);
    }

    public function test_train_model_requires_sufficient_data(): void
    {
        $result = $this->nnService->trainModel();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('message', $result);
    }

    public function test_confidence_reduces_with_missing_data(): void
    {
        // Create property with complete data
        $completeProperty = Property::factory()->create([
            'bedrooms' => 3,
            'bathrooms' => 2,
            'area_sqft' => 1500,
            'year_built' => 2010,
            'property_type' => 'detached',
        ]);

        // Create property with missing data
        $incompleteProperty = Property::factory()->create([
            'bedrooms' => null,
            'bathrooms' => null,
            'area_sqft' => 1500,
            'year_built' => null,
            'property_type' => 'detached',
        ]);

        $completeResult = $this->nnService->generateValuation($completeProperty);
        $incompleteResult = $this->nnService->generateValuation($incompleteProperty);

        $this->assertGreaterThan(
            $incompleteResult['confidence_level'],
            $completeResult['confidence_level']
        );
    }

    public function test_valuation_controller_generates_valuation(): void
    {
        $this->actingAs($this->user);

        $response = $this->postJson(
            route('property.valuation.generate', $this->property)
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'valuation',
            'message'
        ]);

        $this->assertDatabaseHas('property_valuations', [
            'property_id' => $this->property->id,
            'valuation_type' => 'neural_network',
        ]);
    }

    public function test_valuation_controller_requires_authentication(): void
    {
        $response = $this->postJson(
            route('property.valuation.generate', $this->property)
        );

        $response->assertStatus(401);
    }

    public function test_valuation_controller_gets_history(): void
    {
        $this->actingAs($this->user);

        // Create some valuations
        PropertyValuation::factory()->create([
            'property_id' => $this->property->id,
            'valuation_type' => 'neural_network',
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $response = $this->getJson(
            route('property.valuation.history', $this->property)
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'valuations'
        ]);
    }

    public function test_valuation_controller_gets_detailed_report(): void
    {
        $this->actingAs($this->user);

        $response = $this->getJson(
            route('property.valuation.report', $this->property)
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'report' => [
                'property',
                'valuation',
                'comparables',
            ]
        ]);
    }

    public function test_livewire_component_renders(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('property.valuation', ['propertyId' => $this->property->id]));

        $response->assertStatus(200);
        $response->assertSeeLivewire('property-valuation-component');
    }
}
