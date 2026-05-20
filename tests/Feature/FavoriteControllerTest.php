<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Property;
use App\Models\User;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FavoriteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $property;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->property = Property::factory()->create();
    }

    public function test_user_can_view_their_favorites()
    {
        Sanctum::actingAs($this->user);

        Favorite::create([
            'user_id' => $this->user->id,
            'property_id' => $this->property->id,
        ]);

        $response = $this->getJson('/api/favorites');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'title', 'price', 'location']
                     ]
                 ]);
    }

    public function test_user_can_add_property_to_favorites()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/favorites', [
            'property_id' => $this->property->id,
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Property added to wishlist successfully',
                 ]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->user->id,
            'property_id' => $this->property->id,
        ]);
    }

    public function test_user_cannot_add_duplicate_favorite()
    {
        Sanctum::actingAs($this->user);

        Favorite::create([
            'user_id' => $this->user->id,
            'property_id' => $this->property->id,
        ]);

        $response = $this->postJson('/api/favorites', [
            'property_id' => $this->property->id,
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'message' => 'Property is already in your wishlist',
                 ]);
    }

    public function test_user_can_remove_property_from_favorites()
    {
        Sanctum::actingAs($this->user);

        Favorite::create([
            'user_id' => $this->user->id,
            'property_id' => $this->property->id,
        ]);

        $response = $this->deleteJson('/api/favorites/' . $this->property->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Property removed from wishlist successfully',
                 ]);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $this->user->id,
            'property_id' => $this->property->id,
        ]);
    }

    public function test_user_can_check_if_property_is_favorited()
    {
        Sanctum::actingAs($this->user);

        Favorite::create([
            'user_id' => $this->user->id,
            'property_id' => $this->property->id,
        ]);

        $response = $this->getJson('/api/favorites/check/' . $this->property->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'is_favorited' => true,
                 ]);
    }

    public function test_user_cannot_access_favorites_without_authentication()
    {
        $response = $this->getJson('/api/favorites');

        $response->assertStatus(401);
    }

    public function test_add_favorite_requires_valid_property_id()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/favorites', [
            'property_id' => 99999, // Non-existent property
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['property_id']);
    }

    public function test_favorite_includes_team_id_when_user_has_team()
    {
        $team = Team::factory()->create();
        $this->user->current_team_id = $team->id;
        $this->user->save();

        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/favorites', [
            'property_id' => $this->property->id,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->user->id,
            'property_id' => $this->property->id,
            'team_id' => $team->id,
        ]);
    }
}
