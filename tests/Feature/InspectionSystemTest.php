<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Property;
use App\Models\Inspection;

class InspectionSystemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_schedule_inspection()
    {
        $user = User::factory()->create();
        $property = Property::factory()->create();

        $response = $this->actingAs($user)->post('/inspections', [
            'property_id' => $property->id