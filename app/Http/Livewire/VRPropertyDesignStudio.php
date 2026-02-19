<?php

namespace App\Http\Livewire;

use App\Models\Property;
use App\Models\VRDesign;
use App\Services\VRPropertyDesignService;
use Livewire\Component;
use Livewire\WithFileUploads;

class VRPropertyDesignStudio extends Component
{
    use WithFileUploads;

    public Property $property;
    public $designs = [];
    public $selectedDesignId = null;
    public $selectedDesign = null;
    
    // Design creation/edit
    public $showDesignModal = false;
    public $designName = '';
    public $designDescription = '';
    public $designStyle = null;
    public $isPublic = false;
    
    // Furniture addition
    public $showFurnitureModal = false;
    public $furnitureCategory = '';
    public $furnitureType = '';
    public $furniturePositionX = 0;
    public $furniturePositionY = 0;
    public $furniturePositionZ = 0;
    
    // Thumbnail upload
    public $showThumbnailModal = false;
    public $thumbnailUpload = null;

    protected VRPropertyDesignService $vrService;

    public function boot(VRPropertyDesignService $vrService)
    {
        $this->vrService = $vrService;
    }

    public function mount(Property $property)
    {
        $this->property = $property;
        $this->loadDesigns();
    }

    public function loadDesigns()
    {
        $this->designs = $this->vrService->getPropertyDesigns($this->property, false);
        
        if ($this->selectedDesignId && count($this->designs) > 0) {
            $this->loadSelectedDesign();
        }
    }

    public function loadSelectedDesign()
    {
        $this->selectedDesign = $this->vrService->getDesign($this->selectedDesignId);
    }

    public function selectDesign($designId)
    {
        $this->selectedDesignId = $designId;
        $this->loadSelectedDesign();
    }

    public function createNewDesign()
    {
        $this->resetDesignForm();
        $this->showDesignModal = true;
    }

    public function saveDesign()
    {
        $this->validate([
            'designName' => 'required|string|max:255',
            'designDescription' => 'nullable|string|max:1000',
            'designStyle' => 'nullable|string',
        ]);

        try {
            $design = $this->vrService->createDesign(
                $this->property,
                auth()->user(),
                $this->designName,
                ['created_via' => 'livewire'],
                $this->designDescription,
                $this->designStyle,
                $this->isPublic
            );

            $this->loadDesigns();
            $this->selectDesign($design->id);
            $this->showDesignModal = false;
            $this->resetDesignForm();
            
            session()->flash('message', 'VR design created successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create design: ' . $e->getMessage());
        }
    }

    public function editDesign()
    {
        if (!$this->selectedDesign) {
            return;
        }

        $this->designName = $this->selectedDesign->name;
        $this->designDescription = $this->selectedDesign->description;
        $this->designStyle = $this->selectedDesign->style;
        $this->isPublic = $this->selectedDesign->is_public;
        $this->showDesignModal = true;
    }

    public function updateDesign()
    {
        if (!$this->selectedDesign) {
            return;
        }

        $this->validate([
            'designName' => 'required|string|max:255',
            'designDescription' => 'nullable|string|max:1000',
            'designStyle' => 'nullable|string',
        ]);

        try {
            $design = VRDesign::find($this->selectedDesignId);
            
            $this->vrService->updateDesign($design, [
                'name' => $this->designName,
                'description' => $this->designDescription,
                'style' => $this->designStyle,
                'is_public' => $this->isPublic,
            ]);

            $this->loadDesigns();
            $this->loadSelectedDesign();
            $this->showDesignModal = false;
            
            session()->flash('message', 'VR design updated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update design: ' . $e->getMessage());
        }
    }

    public function deleteDesign($designId)
    {
        try {
            $design = VRDesign::findOrFail($designId);
            $this->vrService->deleteDesign($design);

            if ($this->selectedDesignId == $designId) {
                $this->selectedDesignId = null;
                $this->selectedDesign = null;
            }

            $this->loadDesigns();
            session()->flash('message', 'VR design deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete design: ' . $e->getMessage());
        }
    }

    public function openFurnitureModal()
    {
        $this->resetFurnitureForm();
        $this->showFurnitureModal = true;
    }

    public function addFurniture()
    {
        if (!$this->selectedDesign) {
            return;
        }

        $this->validate([
            'furnitureCategory' => 'required|string',
            'furnitureType' => 'required|string',
        ]);

        try {
            $design = VRDesign::find($this->selectedDesignId);
            
            $this->vrService->addFurniture(
                $design,
                $this->furnitureCategory,
                $this->furnitureType,
                [$this->furniturePositionX, $this->furniturePositionY, $this->furniturePositionZ]
            );

            $this->loadSelectedDesign();
            $this->showFurnitureModal = false;
            $this->resetFurnitureForm();
            
            session()->flash('message', 'Furniture added successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add furniture: ' . $e->getMessage());
        }
    }

    public function removeFurniture($furnitureId)
    {
        if (!$this->selectedDesign) {
            return;
        }

        try {
            $design = VRDesign::find($this->selectedDesignId);
            $this->vrService->removeFurniture($design, $furnitureId);

            $this->loadSelectedDesign();
            session()->flash('message', 'Furniture removed successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to remove furniture: ' . $e->getMessage());
        }
    }

    public function cloneDesign()
    {
        if (!$this->selectedDesign) {
            return;
        }

        try {
            $design = VRDesign::find($this->selectedDesignId);
            $clonedDesign = $this->vrService->cloneDesign(
                $design,
                auth()->user(),
                $this->selectedDesign->name . ' (Copy)'
            );

            $this->loadDesigns();
            $this->selectDesign($clonedDesign->id);
            
            session()->flash('message', 'Design cloned successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to clone design: ' . $e->getMessage());
        }
    }

    public function openThumbnailModal()
    {
        $this->showThumbnailModal = true;
    }

    public function uploadThumbnail()
    {
        if (!$this->selectedDesign) {
            return;
        }

        $this->validate([
            'thumbnailUpload' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        try {
            $design = VRDesign::find($this->selectedDesignId);
            $this->vrService->uploadThumbnail($design, $this->thumbnailUpload);

            $this->loadSelectedDesign();
            $this->showThumbnailModal = false;
            $this->thumbnailUpload = null;
            
            session()->flash('message', 'Thumbnail uploaded successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to upload thumbnail: ' . $e->getMessage());
        }
    }

    protected function resetDesignForm()
    {
        $this->designName = '';
        $this->designDescription = '';
        $this->designStyle = null;
        $this->isPublic = false;
    }

    protected function resetFurnitureForm()
    {
        $this->furnitureCategory = '';
        $this->furnitureType = '';
        $this->furniturePositionX = 0;
        $this->furniturePositionY = 0;
        $this->furniturePositionZ = 0;
    }

    public function getDesignStylesProperty()
    {
        return $this->vrService->getDesignStyles();
    }

    public function getFurnitureCategoriesProperty()
    {
        return $this->vrService->getFurnitureCategories();
    }

    public function render()
    {
        return view('livewire.vr-property-design-studio');
    }
}
