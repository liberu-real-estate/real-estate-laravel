<?php

namespace Tests\Unit;

use App\Filament\Staff\Resources\BuyerResource;
use App\Models\Buyer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Forms\ComponentContainer;
use Filament\Tables\Table;

class BuyerResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_buyer_resource_form()
    {
        $this->actingAs(Buyer::factory()->create());

        $form = BuyerResource::form(new ComponentContainer());

        $this->assertNotNull($form->getSchema());
        $this->assertGreaterThan(0, count($form->getSchema()));
    }

    public function test_buyer_resource_table()
    {
        $this->actingAs(Buyer::factory()->create());

        $table = BuyerResource::table(new Table());

        $this->assertNotNull($table->getColumns());
        $this->assertGreaterThan(0, count($table->getColumns()));
    }

    public function test_buyer_resource_relations()
    {
        $relations = BuyerResource::getRelations();

        $this->assertIsArray($relations);
    }

    public function test_buyer_resource_pages()
    {
        $pages = BuyerResource::getPages();

        $this->assertIsArray($pages);
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }
}