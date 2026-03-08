<?php

namespace Tests\Unit;

use App\Filament\Staff\Resources\Appointments\AppointmentResource;
use App\Models\Appointment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Tables\Table;

class AppointmentResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_appointment_resource_form()
    {
        $this->markTestSkipped('Filament form tests require Livewire test setup.');
    }

    public function test_appointment_resource_table()
    {
        $this->markTestSkipped('Filament table tests require Livewire test setup.');
    }

    public function test_appointment_resource_relations()
    {
        $relations = AppointmentResource::getRelations();

        $this->assertIsArray($relations);
    }

    public function test_appointment_resource_pages()
    {
        $pages = AppointmentResource::getPages();

        $this->assertIsArray($pages);
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }
}