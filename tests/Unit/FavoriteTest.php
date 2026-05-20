<?php

namespace Tests\Unit;

use App\Models\Favorite;
use App\Models\Property;
use App\Models\User;
use App\Models\Team;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_favorite()
    {
        $property = Property::factory()->create();
        $user = User::factory()->create();

        $favoriteData = [
            'property_id' => $property->id,
            'user_id' => $user->id,
        ];

        $favorite = Favorite::create($favoriteData);

        $this->assertInstanceOf(Favorite::class, $favorite);
        $this->assertDatabaseHas('favorites', $favoriteData);
    }

    public function test_favorite_relationships()
    {
        $favorite = Favorite::factory()->create();

        $this->assertInstanceOf(Property::class, $favorite->property);
        $this->assertInstanceOf(User::class, $favorite->user);
    }

    public function test_user_can_have_multiple_favorites()
    {
        $user = User::factory()->create();
        $property1 = Property::factory()->create();
        $property2 = Property::factory()->create();

        Favorite::create(['user_id' => $user->id, 'property_id' => $property1->id]);
        Favorite::create(['user_id' => $user->id, 'property_id' => $property2->id]);

        $this->assertCount(2, $user->favorites);
        $this->assertCount(2, $user->favoriteProperties);
    }

    public function test_property_can_be_favorited_by_multiple_users()
    {
        $property = Property::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Favorite::create(['user_id' => $user1->id, 'property_id' => $property->id]);
        Favorite::create(['user_id' => $user2->id, 'property_id' => $property->id]);

        $this->assertCount(2, $property->favorites);
        $this->assertCount(2, $property->favoritedBy);
    }

    public function test_favorite_with_team()
    {
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $property = Property::factory()->create();

        $favorite = Favorite::create([
            'user_id' => $user->id,
            'property_id' => $property->id,
            'team_id' => $team->id,
        ]);

        $this->assertInstanceOf(Team::class, $favorite->team);
        $this->assertEquals($team->id, $favorite->team_id);
    }

    public function test_delete_favorite()
    {
        $favorite = Favorite::factory()->create();
        $favoriteId = $favorite->id;

        $favorite->delete();

        $this->assertDatabaseMissing('favorites', ['id' => $favoriteId]);
    }
}
