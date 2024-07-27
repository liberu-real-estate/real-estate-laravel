<?php

namespace Tests\Unit;

use App\Filament\Staff\Resources\LandlordResource;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Forms\ComponentContainer;
use Filament\Tables\Table;

class LandlordResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_landlord_resource_form()
    {
        $this->actingAs(User::factory()->create());

        $form = LandlordResource::form(new ComponentContainer());

        $this->assertNotNull($form->getSchema());
        $this->assertGreaterThan(0, count($form->getSchema()));
    }

    public function test_landlord_resource_table()
    {
        $this->actingAs(User::factory()->create());

        $table = LandlordResource::table(new Table());

        $this->assertNotNull($table->getColumns());
        $this->assertGreaterThan(0, count($table->getColumns()));
    }

    public function test_landlord_resource_relations()
    {
        $relations = LandlordResource::getRelations();

        $this->assertIsArray($relations);
    }

    public function test_landlord_resource_pages()
    {
        $pages = LandlordResource::getPages();

        $this->assertIsArray($pages);
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }
}