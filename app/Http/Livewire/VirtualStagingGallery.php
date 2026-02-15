<?php

namespace App\Http\Livewire;

use App\Models\Property;
use App\Services\VirtualStagingService;
use Livewire\Component;
use Livewire\WithFileUploads;

class VirtualStagingGallery extends Component
{
    use WithFileUploads;

    public Property $property;
    public $images = [];
    public $selectedImageId = null;
    public $selectedStagingStyle = null;
    public $uploadedImage = null;
    public $showUploadModal = false;
    public $showStagingModal = false;
    public $autoStage = false;

    protected VirtualStagingService $stagingService;

    public function boot(VirtualStagingService $stagingService)
    {
        $this->stagingService = $stagingService;
    }

    public function mount(Property $property)
    {
        $this->property = $property;
        $this->loadImages();
    }

    public function loadImages()
    {
        $this->images = $this->property->images()
            ->with(['stagedVersions', 'originalImage'])
            ->get()
            ->toArray();
    }

    public function uploadImage()
    {
        $this->validate([
            'uploadedImage' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'selectedStagingStyle' => 'nullable|string|in:' . implode(',', array_keys(VirtualStagingService::STAGING_STYLES)),
        ]);

        try {
            $image = app(VirtualStagingService::class)->uploadImage(
                $this->property,
                $this->uploadedImage,
                $this->selectedStagingStyle,
                $this->autoStage
            );

            $this->loadImages();
            $this->reset(['uploadedImage', 'selectedStagingStyle', 'autoStage', 'showUploadModal']);
            $this->dispatch('image-uploaded', ['imageId' => $image->image_id]);
            session()->flash('message', 'Image uploaded successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to upload image: ' . $e->getMessage());
        }
    }

    public function stageExistingImage($imageId)
    {
        $this->selectedImageId = $imageId;
        $this->showStagingModal = true;
    }

    public function applyStaging()
    {
        $this->validate([
            'selectedStagingStyle' => 'required|string|in:' . implode(',', array_keys(VirtualStagingService::STAGING_STYLES)),
        ]);

        try {
            $image = \App\Models\Image::findOrFail($this->selectedImageId);
            
            if ($image->is_staged) {
                session()->flash('error', 'Cannot stage an already staged image.');
                return;
            }

            app(VirtualStagingService::class)->stageImage($image, $this->selectedStagingStyle);

            $this->loadImages();
            $this->reset(['selectedImageId', 'selectedStagingStyle', 'showStagingModal']);
            session()->flash('message', 'Image staged successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to stage image: ' . $e->getMessage());
        }
    }

    public function deleteImage($imageId)
    {
        try {
            $image = \App\Models\Image::findOrFail($imageId);
            app(VirtualStagingService::class)->deleteImage($image);

            $this->loadImages();
            session()->flash('message', 'Image deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete image: ' . $e->getMessage());
        }
    }

    public function getStagingStylesProperty()
    {
        return VirtualStagingService::STAGING_STYLES;
    }

    public function render()
    {
        return view('livewire.virtual-staging-gallery');
    }
}
