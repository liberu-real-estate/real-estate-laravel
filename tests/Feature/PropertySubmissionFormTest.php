<?php

namespace Tests\Feature;

use App\Http\Livewire\PropertySubmissionForm;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PropertySubmissionFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_property_submission_form_renders()
    {
        $user = User::factory()->create();
        
        Livewire::actingAs($user)
            ->test(PropertySubmissionForm::class)
            ->assertStatus(200);
    }

    public function test_property_can_be_submitted_with_video()
    {
        $user = User::factory()->create();
        $video = UploadedFile::fake()->create('property-video.mp4', 50000, 'video/mp4');

        Livewire::actingAs($user)
            ->test(PropertySubmissionForm::class)
            ->set('title', 'Test Property')
            ->set('description', 'A test property description')
            ->set('location', 'Test Location')
            ->set('price', 250000)
            ->set('bedrooms', 3)
            ->set('bathrooms', 2)
            ->set('area_sqft', 1500)
            ->set('year_built', 2020)
            ->set('property_type', 'House')
            ->set('video', $video)
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertCount(1, Property::all());
        
        $property = Property::first();
        $this->assertEquals('Test Property', $property->title);
        $this->assertCount(1, $property->getMedia('videos'));
    }

    public function test_property_can_be_submitted_without_video()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(PropertySubmissionForm::class)
            ->set('title', 'Test Property')
            ->set('description', 'A test property description')
            ->set('location', 'Test Location')
            ->set('price', 250000)
            ->set('bedrooms', 3)
            ->set('bathrooms', 2)
            ->set('area_sqft', 1500)
            ->set('year_built', 2020)
            ->set('property_type', 'House')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertCount(1, Property::all());
        
        $property = Property::first();
        $this->assertEquals('Test Property', $property->title);
        $this->assertCount(0, $property->getMedia('videos'));
    }

    public function test_video_validation_rejects_invalid_mime_type()
    {
        $user = User::factory()->create();
        $invalidVideo = UploadedFile::fake()->create('property-video.avi', 1000, 'video/avi');

        Livewire::actingAs($user)
            ->test(PropertySubmissionForm::class)
            ->set('title', 'Test Property')
            ->set('description', 'A test property description')
            ->set('location', 'Test Location')
            ->set('price', 250000)
            ->set('bedrooms', 3)
            ->set('bathrooms', 2)
            ->set('area_sqft', 1500)
            ->set('year_built', 2020)
            ->set('property_type', 'House')
            ->set('video', $invalidVideo)
            ->call('submit')
            ->assertHasErrors(['video']);
    }

    public function test_required_fields_are_validated()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(PropertySubmissionForm::class)
            ->call('submit')
            ->assertHasErrors(['title', 'description', 'location', 'price', 'bedrooms', 'bathrooms', 'area_sqft', 'year_built', 'property_type']);
    }
}
