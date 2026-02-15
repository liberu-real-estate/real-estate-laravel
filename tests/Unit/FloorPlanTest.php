<?php

namespace Tests\Unit;

use App\Models\Property;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FloorPlanTest extends TestCase
{
    use RefreshDatabase;

    private const SAMPLE_BASE64_IMAGE = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

    private function getSampleFloorPlanData(): array
    {
        return [
            'image' => self::SAMPLE_BASE64_IMAGE,
            'annotations' => [
                [
                    'type' => 'room',
                    'x' => 100,
                    'y' => 150,
                    'label' => 'Living Room'
                ],
                [
                    'type' => 'marker',
                    'x' => 200,
                    'y' => 250,
                    'label' => 'Balcony'
                ]
            ]
        ];
    }

    public function test_property_can_have_floor_plan_data()
    {
        $floorPlanData = $this->getSampleFloorPlanData();

        $property = Property::factory()->create([
            'floor_plan_data' => $floorPlanData
        ]);

        $this->assertInstanceOf(Property::class, $property);
        $this->assertIsArray($property->floor_plan_data);
        $this->assertEquals($floorPlanData, $property->floor_plan_data);
    }

    public function test_property_floor_plan_data_can_be_null()
    {
        $property = Property::factory()->create([
            'floor_plan_data' => null
        ]);

        $this->assertNull($property->floor_plan_data);
    }

    public function test_property_floor_plan_data_is_cast_to_array()
    {
        $property = Property::factory()->create([
            'floor_plan_data' => ['test' => 'data']
        ]);

        $this->assertIsArray($property->floor_plan_data);
    }

    public function test_property_can_have_floor_plan_image()
    {
        $property = Property::factory()->create([
            'floor_plan_image' => 'floor-plans/test-property.png'
        ]);

        $this->assertEquals('floor-plans/test-property.png', $property->floor_plan_image);
    }

    public function test_floor_plan_annotations_structure()
    {
        $annotations = [
            [
                'type' => 'room',
                'x' => 100,
                'y' => 150,
                'label' => 'Kitchen'
            ],
            [
                'type' => 'room',
                'x' => 300,
                'y' => 150,
                'label' => 'Bedroom'
            ],
            [
                'type' => 'marker',
                'x' => 200,
                'y' => 50,
                'label' => 'Main Entrance'
            ]
        ];

        $floorPlanData = [
            'image' => self::SAMPLE_BASE64_IMAGE,
            'annotations' => $annotations
        ];

        $property = Property::factory()->create([
            'floor_plan_data' => $floorPlanData
        ]);

        $this->assertCount(3, $property->floor_plan_data['annotations']);
        $this->assertEquals('room', $property->floor_plan_data['annotations'][0]['type']);
        $this->assertEquals('marker', $property->floor_plan_data['annotations'][2]['type']);
    }
}
