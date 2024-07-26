<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_can_be_hmo()
    {
        $hmoCategory = Property