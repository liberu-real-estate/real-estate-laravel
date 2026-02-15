<?php

namespace Tests\Unit;

use App\Models\Image;
use App\Models\Property;
use App\Models\Team;
use App\Services\VirtualStagingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VirtualStagingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected VirtualStagingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new VirtualStagingService();
        Storage::fake('public');
    }

    /** @test */
    public function it_can_get_staging_styles()
    {
        $styles = $this->service->getStagingStyles();

        $this->assertIsArray($styles);
        $this->assertNotEmpty($styles);
        $this->assertArrayHasKey('modern', $styles);
        $this->assertArrayHasKey('traditional', $styles);
    }

    /** @test */
    public function it_can_upload_an_image()
    {
        $team = Team::factory()->create();
        $property = Property::factory()->create(['team_id' => $team->id]);
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);

        $image = $this->service->uploadImage($property, $file);

        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals($property->id, $image->property_id);
        $this->assertEquals($team->id, $image->team_id);
        $this->assertFalse($image->is_staged);
        $this->assertNotNull($image->file_path);
        Storage::disk('public')->assertExists($image->file_path);
    }

    /** @test */
    public function it_can_upload_and_auto_stage_an_image()
    {
        $team = Team::factory()->create();
        $property = Property::factory()->create(['team_id' => $team->id]);
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);

        $image = $this->service->uploadImage($property, $file, 'modern', true);

        $this->assertInstanceOf(Image::class, $image);
        $this->assertFalse($image->is_staged); // Original should not be staged
        
        // Check if staged version was created
        $stagedVersions = $image->stagedVersions;
        $this->assertCount(1, $stagedVersions);
        $this->assertTrue($stagedVersions->first()->is_staged);
        $this->assertEquals('modern', $stagedVersions->first()->staging_style);
    }

    /** @test */
    public function it_can_stage_an_existing_image()
    {
        $team = Team::factory()->create();
        $property = Property::factory()->create(['team_id' => $team->id]);
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);

        $originalImage = $this->service->uploadImage($property, $file);
        $stagedImage = $this->service->stageImage($originalImage, 'luxury');

        $this->assertInstanceOf(Image::class, $stagedImage);
        $this->assertTrue($stagedImage->is_staged);
        $this->assertEquals('luxury', $stagedImage->staging_style);
        $this->assertEquals($originalImage->image_id, $stagedImage->original_image_id);
        $this->assertEquals($property->id, $stagedImage->property_id);
        Storage::disk('public')->assertExists($stagedImage->file_path);
    }

    /** @test */
    public function it_throws_exception_for_invalid_staging_style()
    {
        $this->expectException(\InvalidArgumentException::class);

        $team = Team::factory()->create();
        $property = Property::factory()->create(['team_id' => $team->id]);
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);
        $originalImage = $this->service->uploadImage($property, $file);

        $this->service->stageImage($originalImage, 'invalid_style');
    }

    /** @test */
    public function it_can_delete_an_image_with_staged_versions()
    {
        $team = Team::factory()->create();
        $property = Property::factory()->create(['team_id' => $team->id]);
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);

        $originalImage = $this->service->uploadImage($property, $file);
        $stagedImage1 = $this->service->stageImage($originalImage, 'modern');
        $stagedImage2 = $this->service->stageImage($originalImage, 'luxury');

        $originalPath = $originalImage->file_path;
        $stagedPath1 = $stagedImage1->file_path;
        $stagedPath2 = $stagedImage2->file_path;

        $this->service->deleteImage($originalImage);

        $this->assertNull(Image::find($originalImage->image_id));
        $this->assertNull(Image::find($stagedImage1->image_id));
        $this->assertNull(Image::find($stagedImage2->image_id));
        
        Storage::disk('public')->assertMissing($originalPath);
        Storage::disk('public')->assertMissing($stagedPath1);
        Storage::disk('public')->assertMissing($stagedPath2);
    }

    /** @test */
    public function it_can_get_property_images()
    {
        $team = Team::factory()->create();
        $property = Property::factory()->create(['team_id' => $team->id]);
        
        $file1 = UploadedFile::fake()->image('test1.jpg');
        $file2 = UploadedFile::fake()->image('test2.jpg');
        
        $image1 = $this->service->uploadImage($property, $file1);
        $image2 = $this->service->uploadImage($property, $file2);
        $this->service->stageImage($image1, 'modern');

        $images = $this->service->getPropertyImages($property, true);

        $this->assertCount(3, $images); // 2 originals + 1 staged
    }

    /** @test */
    public function it_can_get_only_original_images()
    {
        $team = Team::factory()->create();
        $property = Property::factory()->create(['team_id' => $team->id]);
        
        $file1 = UploadedFile::fake()->image('test1.jpg');
        $file2 = UploadedFile::fake()->image('test2.jpg');
        
        $image1 = $this->service->uploadImage($property, $file1);
        $image2 = $this->service->uploadImage($property, $file2);
        $this->service->stageImage($image1, 'modern');

        $images = $this->service->getPropertyImages($property, false);

        $this->assertCount(2, $images); // Only originals
        foreach ($images as $image) {
            $this->assertFalse($image->is_staged);
        }
    }
}
