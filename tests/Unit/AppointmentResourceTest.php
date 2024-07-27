<?php

namespace Tests\Unit;

use App\Filament\Staff\Resources\AppointmentResource;
use App\Models\Appointment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Forms\ComponentContainer;
use Filament\Tables\Table;

class AppointmentResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_appointment_resource_form()
    {
        $this->actingAs(Appointment::factory()->create());

        $form = AppointmentResource::form(new ComponentContainer());

        $this->assertNotNull($form->getSchema());
        $this->assertGreaterThan(0, count($form->getSchema()));
    }

    public function test_appointment_resource_table()
    {
        $this->actingAs(Appointment::factory()->create());

        $table = AppointmentResource::table(new Table());

        $this->assertNotNull($table->getColumns());
        $this->assertGreaterThan(0, count($table->getColumns()));
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