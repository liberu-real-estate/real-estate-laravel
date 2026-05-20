<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Models\User;
use App\Models\VRDesign;
use App\Models\Team;
use App\Services\VRPropertyDesignService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class VRPropertyDesignServiceTest extends TestCase
{
    use RefreshDatabase;

    protected VRPropertyDesignService $service;
    protected User $user;
    protected Property $property;
    protected Team $team;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = new VRPropertyDesignService();
        
        // Create test data
        $this->team = Team::factory()->create();
        $this->user = User::factory()->create([
            'current_team_id' => $this->team->id,
        ]);
        $this->property = Property::factory()->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
    }

    public function test_get_design_styles()
    {
        $styles = $this->service->getDesignStyles();
        
        $this->assertIsArray($styles);
        $this->assertArrayHasKey('modern', $styles);
        $this->assertArrayHasKey('traditional', $styles);
        $this->assertArrayHasKey('minimalist', $styles);
    }

    public function test_get_furniture_categories()
    {
        $categories = $this->service->getFurnitureCategories();
        
        $this->assertIsArray($categories);
        $this->assertArrayHasKey('seating', $categories);
        $this->assertArrayHasKey('tables', $categories);
        $this->assertArrayHasKey('storage', $categories);
    }

    public function test_get_room_types()
    {
        $roomTypes = $this->service->getRoomTypes();
        
        $this->assertIsArray($roomTypes);
        $this->assertArrayHasKey('living_room', $roomTypes);
        $this->assertArrayHasKey('bedroom', $roomTypes);
    }

    public function test_get_supported_devices()
    {
        $devices = $this->service->getSupportedDevices();
        
        $this->assertIsArray($devices);
        $this->assertNotEmpty($devices);
    }

    public function test_create_design()
    {
        $design = $this->service->createDesign(
            $this->property,
            $this->user,
            'Test Design',
            ['test' => 'data'],
            'A test design',
            'modern',
            false
        );

        $this->assertInstanceOf(VRDesign::class, $design);
        $this->assertEquals('Test Design', $design->name);
        $this->assertEquals('A test design', $design->description);
        $this->assertEquals('modern', $design->style);
        $this->assertEquals($this->property->id, $design->property_id);
        $this->assertEquals($this->user->id, $design->user_id);
        $this->assertFalse($design->is_public);
        
        $this->assertDatabaseHas('vr_designs', [
            'name' => 'Test Design',
            'property_id' => $this->property->id,
        ]);
    }

    public function test_update_design()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'name' => 'Original Name',
        ]);

        $updatedDesign = $this->service->updateDesign($design, [
            'name' => 'Updated Name',
            'description' => 'Updated description',
            'style' => 'luxury',
        ]);

        $this->assertEquals('Updated Name', $updatedDesign->name);
        $this->assertEquals('Updated description', $updatedDesign->description);
        $this->assertEquals('luxury', $updatedDesign->style);
        
        $this->assertDatabaseHas('vr_designs', [
            'id' => $design->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_delete_design()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $designId = $design->id;
        $result = $this->service->deleteDesign($design);

        $this->assertTrue($result);
        $this->assertSoftDeleted('vr_designs', ['id' => $designId]);
    }

    public function test_get_property_designs()
    {
        VRDesign::factory()->count(3)->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $designs = $this->service->getPropertyDesigns($this->property);

        $this->assertIsArray($designs);
        $this->assertCount(3, $designs);
    }

    public function test_get_property_designs_public_only()
    {
        VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'is_public' => true,
        ]);

        VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'is_public' => false,
        ]);

        $designs = $this->service->getPropertyDesigns($this->property, true);

        $this->assertIsArray($designs);
        $this->assertCount(1, $designs);
    }

    public function test_get_design()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $retrievedDesign = $this->service->getDesign($design->id);

        $this->assertInstanceOf(VRDesign::class, $retrievedDesign);
        $this->assertEquals($design->id, $retrievedDesign->id);
    }

    public function test_add_furniture()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $updatedDesign = $this->service->addFurniture(
            $design,
            'seating',
            'Sofa',
            [0, 0, 0],
            [0, 90, 0],
            [1, 1, 1],
            ['color' => 'blue']
        );

        $this->assertNotEmpty($updatedDesign->furniture_items);
        $this->assertCount(1, $updatedDesign->furniture_items);
        
        $furniture = $updatedDesign->furniture_items[0];
        $this->assertEquals('seating', $furniture['category']);
        $this->assertEquals('Sofa', $furniture['type']);
        $this->assertEquals([0, 0, 0], $furniture['position']);
    }

    public function test_remove_furniture()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'furniture_items' => [
                [
                    'id' => 'furniture_123',
                    'category' => 'seating',
                    'type' => 'Sofa',
                    'position' => [0, 0, 0],
                    'rotation' => [0, 0, 0],
                    'scale' => [1, 1, 1],
                    'material' => [],
                    'created_at' => now()->toIso8601String(),
                ],
            ],
        ]);

        $updatedDesign = $this->service->removeFurniture($design, 'furniture_123');

        $this->assertEmpty($updatedDesign->furniture_items);
    }

    public function test_update_room_layout()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $layout = [
            'width' => 10,
            'height' => 8,
            'depth' => 12,
        ];

        $updatedDesign = $this->service->updateRoomLayout($design, $layout);

        $this->assertEquals($layout, $updatedDesign->room_layout);
    }

    public function test_update_materials()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $materials = [
            'walls' => ['color' => '#FFFFFF', 'texture' => 'smooth'],
            'floor' => ['material' => 'wood', 'color' => '#8B4513'],
        ];

        $updatedDesign = $this->service->updateMaterials($design, $materials);

        $this->assertEquals($materials, $updatedDesign->materials);
    }

    public function test_update_lighting()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $lighting = [
            'ambient' => ['intensity' => 0.5, 'color' => '#FFFFFF'],
            'directional' => ['intensity' => 1.0, 'position' => [5, 10, 5]],
        ];

        $updatedDesign = $this->service->updateLighting($design, $lighting);

        $this->assertEquals($lighting, $updatedDesign->lighting);
    }

    public function test_clone_design()
    {
        $originalDesign = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'name' => 'Original Design',
            'view_count' => 100,
        ]);

        $clonedDesign = $this->service->cloneDesign(
            $originalDesign,
            $this->user,
            'Cloned Design'
        );

        $this->assertNotEquals($originalDesign->id, $clonedDesign->id);
        $this->assertEquals('Cloned Design', $clonedDesign->name);
        $this->assertEquals($this->user->id, $clonedDesign->user_id);
        $this->assertEquals(0, $clonedDesign->view_count);
        $this->assertFalse($clonedDesign->is_template);
    }

    public function test_create_template()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'is_template' => false,
            'is_public' => false,
        ]);

        $template = $this->service->createTemplate($design, 'Template Design');

        $this->assertNotEquals($design->id, $template->id);
        $this->assertEquals('Template Design', $template->name);
        $this->assertTrue($template->is_template);
        $this->assertTrue($template->is_public);
    }

    public function test_get_templates()
    {
        VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'is_template' => true,
            'is_public' => true,
        ]);

        VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'is_template' => false,
        ]);

        $templates = $this->service->getTemplates();

        $this->assertIsArray($templates);
        $this->assertCount(1, $templates);
    }

    public function test_get_templates_by_style()
    {
        VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'is_template' => true,
            'style' => 'modern',
        ]);

        VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'is_template' => true,
            'style' => 'traditional',
        ]);

        $templates = $this->service->getTemplates('modern');

        $this->assertIsArray($templates);
        $this->assertCount(1, $templates);
    }

    public function test_upload_thumbnail()
    {
        Storage::fake('public');

        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $file = UploadedFile::fake()->image('thumbnail.jpg');

        $updatedDesign = $this->service->uploadThumbnail($design, $file);

        $this->assertNotNull($updatedDesign->thumbnail_path);
        Storage::disk('public')->assertExists($updatedDesign->thumbnail_path);
    }

    public function test_export_design()
    {
        $design = VRDesign::factory()->create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'name' => 'Export Test',
            'design_data' => ['test' => 'data'],
        ]);

        $exportedData = $this->service->exportDesign($design);

        $this->assertIsArray($exportedData);
        $this->assertEquals($design->id, $exportedData['id']);
        $this->assertEquals('Export Test', $exportedData['name']);
        $this->assertArrayHasKey('design_data', $exportedData);
        $this->assertArrayHasKey('metadata', $exportedData);
    }
}
