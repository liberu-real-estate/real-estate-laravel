<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Property;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VirtualStagingApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Team $team;
    protected Property $property;

    protected function setUp(): void
    {
        parent::setUp();
        
        Storage::fake('public');
        
        $this->team = Team::factory()->create();
        $this->user = User::factory()->create();
        $this->user->teams()->attach($this->team);
        $this->property = Property::factory()->create(['team_id' => $this->team->id]);
    }

    /** @test */
    public function it_can_get_staging_styles()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/staging/styles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'styles'
                ]
            ]);

        $this->assertTrue($response->json('success'));
        $this->assertIsArray($response->json('data.styles'));
    }

    /** @test */
    public function it_can_upload_an_image_to_property()
    {
        $file = UploadedFile::fake()->image('property.jpg', 800, 600);

        $response = $this->actingAs($this->user)
            ->postJson("/api/properties/{$this->property->id}/images/upload", [
                'image' => $file,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'image' => [
                        'id',
                        'property_id',
                        'file_name',
                        'url',
                        'is_staged',
                    ]
                ]
            ]);

        $this->assertTrue($response->json('success'));
        $this->assertFalse($response->json('data.image.is_staged'));
        $this->assertEquals($this->property->id, $response->json('data.image.property_id'));
    }

    /** @test */
    public function it_can_upload_and_auto_stage_an_image()
    {
        $file = UploadedFile::fake()->image('property.jpg', 800, 600);

        $response = $this->actingAs($this->user)
            ->postJson("/api/properties/{$this->property->id}/images/upload", [
                'image' => $file,
                'staging_style' => 'modern',
                'auto_stage' => true,
            ]);

        $response->assertStatus(201);
        $this->assertTrue($response->json('success'));
        
        // Original image should not be staged
        $this->assertFalse($response->json('data.image.is_staged'));
        
        // Should have a staged version
        $this->assertTrue($response->json('data.image.has_staged_versions'));
    }

    /** @test */
    public function it_validates_image_upload()
    {
        $response = $this->actingAs($this->user)
            ->postJson("/api/properties/{$this->property->id}/images/upload", [
                'image' => 'not-an-image',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    /** @test */
    public function it_validates_staging_style()
    {
        $file = UploadedFile::fake()->image('property.jpg');

        $response = $this->actingAs($this->user)
            ->postJson("/api/properties/{$this->property->id}/images/upload", [
                'image' => $file,
                'staging_style' => 'invalid-style',
                'auto_stage' => true,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['staging_style']);
    }

    /** @test */
    public function it_can_stage_an_existing_image()
    {
        $file = UploadedFile::fake()->image('property.jpg');
        $image = Image::create([
            'property_id' => $this->property->id,
            'team_id' => $this->team->id,
            'file_path' => $file->store('property-images', 'public'),
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'is_staged' => false,
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/images/{$image->image_id}/stage", [
                'staging_style' => 'luxury',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'staged_image' => [
                        'id',
                        'url',
                        'is_staged',
                        'staging_style',
                    ]
                ]
            ]);

        $this->assertTrue($response->json('success'));
        $this->assertTrue($response->json('data.staged_image.is_staged'));
        $this->assertEquals('luxury', $response->json('data.staged_image.staging_style'));
    }

    /** @test */
    public function it_cannot_stage_an_already_staged_image()
    {
        $image = Image::create([
            'property_id' => $this->property->id,
            'team_id' => $this->team->id,
            'file_path' => 'test.jpg',
            'file_name' => 'test.jpg',
            'mime_type' => 'image/jpeg',
            'is_staged' => true,
            'staging_style' => 'modern',
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/images/{$image->image_id}/stage", [
                'staging_style' => 'luxury',
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
            ]);
    }

    /** @test */
    public function it_can_get_property_images()
    {
        $file1 = UploadedFile::fake()->image('property1.jpg');
        $file2 = UploadedFile::fake()->image('property2.jpg');
        
        Image::create([
            'property_id' => $this->property->id,
            'team_id' => $this->team->id,
            'file_path' => $file1->store('property-images', 'public'),
            'file_name' => 'property1.jpg',
            'mime_type' => 'image/jpeg',
            'is_staged' => false,
        ]);

        Image::create([
            'property_id' => $this->property->id,
            'team_id' => $this->team->id,
            'file_path' => $file2->store('property-images', 'public'),
            'file_name' => 'property2.jpg',
            'mime_type' => 'image/jpeg',
            'is_staged' => false,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson("/api/properties/{$this->property->id}/images");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'images'
                ]
            ]);

        $this->assertTrue($response->json('success'));
        $this->assertCount(2, $response->json('data.images'));
    }

    /** @test */
    public function it_can_delete_an_image()
    {
        $image = Image::create([
            'property_id' => $this->property->id,
            'team_id' => $this->team->id,
            'file_path' => 'test.jpg',
            'file_name' => 'test.jpg',
            'mime_type' => 'image/jpeg',
            'is_staged' => false,
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/images/{$image->image_id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertNull(Image::find($image->image_id));
    }

    /** @test */
    public function unauthenticated_users_cannot_access_api()
    {
        $response = $this->getJson('/api/staging/styles');
        $response->assertStatus(401);

        $file = UploadedFile::fake()->image('property.jpg');
        $response = $this->postJson("/api/properties/{$this->property->id}/images/upload", [
            'image' => $file,
        ]);
        $response->assertStatus(401);
    }
}
