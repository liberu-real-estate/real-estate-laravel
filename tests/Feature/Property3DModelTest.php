<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\User;
use App\Models\Team;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Property3DModelTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $team;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user and team for authentication
        $this->team = Team::factory()->create();
        $this->user = User::factory()->create();
        $this->user->teams()->attach($this->team);
    }

    public function test_property_can_have_3d_model_url()
    {
        $property = Property::factory()->create([
            'title' => 'Property with 3D Model',
            'model_3d_url' => 'https://example.com/models/property.glb',
        ]);

        $this->assertNotNull($property->model_3d_url);
        $this->assertEquals('https://example.com/models/property.glb', $property->model_3d_url);
        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'model_3d_url' => 'https://example.com/models/property.glb',
        ]);
    }

    public function test_property_can_be_created_without_3d_model_url()
    {
        $property = Property::factory()->create([
            'title' => 'Property without 3D Model',
            'model_3d_url' => null,
        ]);

        $this->assertNull($property->model_3d_url);
        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'model_3d_url' => null,
        ]);
    }

    public function test_3d_model_viewer_appears_on_property_detail_page_when_url_exists()
    {
        $property = Property::factory()->create([
            'title' => 'Property with 3D Model',
            'model_3d_url' => 'https://example.com/models/property.glb',
            'team_id' => $this->team->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('property.detail', ['propertyId' => $property->id]));

        $response->assertStatus(200);
        $response->assertSee('3D Property Model');
        $response->assertSee('3D Model View');
    }

    public function test_3d_model_viewer_does_not_appear_when_url_is_null()
    {
        $property = Property::factory()->create([
            'title' => 'Property without 3D Model',
            'model_3d_url' => null,
            'team_id' => $this->team->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('property.detail', ['propertyId' => $property->id]));

        $response->assertStatus(200);
        $response->assertDontSee('3D Property Model');
        $response->assertDontSee('3D Model View');
    }

    public function test_property_model_3d_url_is_fillable()
    {
        $property = Property::factory()->make();
        
        $property->fill([
            'model_3d_url' => 'https://example.com/models/property.glb',
        ]);

        $this->assertEquals('https://example.com/models/property.glb', $property->model_3d_url);
    }

    public function test_property_3d_model_url_can_be_updated()
    {
        $property = Property::factory()->create([
            'model_3d_url' => 'https://example.com/models/old-model.glb',
        ]);

        $property->update([
            'model_3d_url' => 'https://example.com/models/new-model.glb',
        ]);

        $this->assertEquals('https://example.com/models/new-model.glb', $property->model_3d_url);
        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'model_3d_url' => 'https://example.com/models/new-model.glb',
        ]);
    }

    public function test_3d_model_url_can_be_removed()
    {
        $property = Property::factory()->create([
            'model_3d_url' => 'https://example.com/models/property.glb',
        ]);

        $property->update([
            'model_3d_url' => null,
        ]);

        $this->assertNull($property->model_3d_url);
        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'model_3d_url' => null,
        ]);
    }

    public function test_multiple_properties_can_have_different_3d_models()
    {
        $property1 = Property::factory()->create([
            'model_3d_url' => 'https://example.com/models/property1.glb',
        ]);

        $property2 = Property::factory()->create([
            'model_3d_url' => 'https://example.com/models/property2.glb',
        ]);

        $property3 = Property::factory()->create([
            'model_3d_url' => null,
        ]);

        $this->assertEquals('https://example.com/models/property1.glb', $property1->model_3d_url);
        $this->assertEquals('https://example.com/models/property2.glb', $property2->model_3d_url);
        $this->assertNull($property3->model_3d_url);
    }
}
