<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\Resources\KeyLocationResource;

class KeyLocationResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testFormSchema(): void
    {
        $form = KeyLocationResource::form(null);
        $schema = collect($form->getSchema());

        $locationNameField = $schema->firstWhere('name', 'location_name');
        $addressField = $schema->firstWhere('name', 'address');

        $this->assertNotNull($locationNameField);
        $this->assertTrue($locationNameField->isRequired());
        $this->assertNotNull($addressField);
        $this->assertTrue($addressField->isRequired());
    }

    public function testTableColumns(): void
    {
        $table = KeyLocationResource::table(null);
        $columns = collect($table->getColumns());

        $this->assertTrue($columns->contains('name', 'location_name'));
        $this->assertTrue($columns->contains('name', 'address'));
    }

    public function testPageRoutes(): void
    {
        $pages = KeyLocationResource::getPages();

        $this->assertArrayHasKey('index', $pages);
        $this->get(route($pages['index']))->assertStatus(200);

        $this->assertArrayHasKey('create', $pages);
        $this->get(route($pages['create']))->assertStatus(200);

        $this->assertArrayHasKey('edit', $pages);
        // Assuming a record with ID 1 exists for testing purposes
        $this->get(route($pages['edit'], ['record' => 1]))->assertStatus(200);
    }
}
