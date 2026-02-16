<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Models\User;
use App\Models\Team;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Property3DModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_property_can_have_3d_model_media_collection()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        // Check that the property has the 3d_models media collection registered
        $this->assertTrue(method_exists($property, 'registerMediaCollections'));
        
        // Create a fake 3D model file
        $file = UploadedFile::fake()->create('model.glb', 1024, 'model/gltf-binary');
        
        // Add the file to the collection
        $media = $property->addMedia($file)->toMediaCollection('3d_models');
        
        $this->assertNotNull($media);
        $this->assertEquals('3d_models', $media->collection_name);
    }

    public function test_property_can_check_if_has_3d_model()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        // Initially should not have 3D model
        $this->assertFalse($property->hasMedia('3d_models'));

        // Add a 3D model
        $file = UploadedFile::fake()->create('model.glb', 1024, 'model/gltf-binary');
        $property->addMedia($file)->toMediaCollection('3d_models');

        // Now should have 3D model
        $this->assertTrue($property->hasMedia('3d_models'));
    }

    public function test_property_can_retrieve_3d_model_url()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        $file = UploadedFile::fake()->create('model.glb', 1024, 'model/gltf-binary');
        $property->addMedia($file)->toMediaCollection('3d_models');

        $url = $property->getFirstMediaUrl('3d_models');
        
        $this->assertNotEmpty($url);
        $this->assertIsString($url);
    }

    public function test_property_can_only_have_one_3d_model()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        
        $property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        // Add first 3D model
        $file1 = UploadedFile::fake()->create('model1.glb', 1024, 'model/gltf-binary');
        $property->addMedia($file1)->toMediaCollection('3d_models');

        // Add second 3D model (should replace the first due to singleFile())
        $file2 = UploadedFile::fake()->create('model2.glb', 1024, 'model/gltf-binary');
        $property->addMedia($file2)->toMediaCollection('3d_models');

        // Should only have one 3D model
        $this->assertEquals(1, $property->getMedia('3d_models')->count());
    }

    public function test_migration_adds_model_3d_url_field()
    {
        $this->assertTrue(
            \Illuminate\Support\Facades\Schema::hasColumn('properties', 'model_3d_url')
        );
    }

    public function test_property_model_includes_model_3d_url_in_fillable()
    {
        $property = new Property();
        $this->assertContains('model_3d_url', $property->getFillable());
    }
}
