<?php

namespace Tests\Unit;

use App\Filament\Contractors\Resources\ContractorResource;
use App\Models\Contractor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Forms\ComponentContainer;
use Filament\Tables\Table;

class ContractorResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_contractor_resource_form()
    {
        $this->actingAs(Contractor::factory()->create());

        $form = ContractorResource::form(new ComponentContainer());

        $this->assertNotNull($form->getSchema());
        $this->assertGreaterThan(0, count($form->getSchema()));
    }

    public function test_contractor_resource_table()
    {
        $this->actingAs(Contractor::factory()->create());

        $table = ContractorResource::table(new Table());

        $this->assertNotNull($table->getColumns());
        $this->assertGreaterThan(0, count($table->getColumns()));
    }

    public function test_contractor_resource_relations()
    {
        $relations = ContractorResource::getRelations();

        $this->assertIsArray($relations);
    }

    public function test_contractor_resource_pages()
    {
        $pages = ContractorResource::getPages();

        $this->assertIsArray($pages);
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }
}