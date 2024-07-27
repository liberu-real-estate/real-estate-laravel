<?php

namespace Tests\Unit;

use App\Filament\Staff\Resources\SellerResource;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Forms\ComponentContainer;
use Filament\Tables\Table;

class SellerResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_resource_form()
    {
        $this->actingAs(User::factory()->create());

        $form = SellerResource::form(new ComponentContainer());

        $this->assertNotNull($form->getSchema());
        $this->assertGreaterThan(0, count($form->getSchema()));
    }

    public function test_seller_resource_table()
    {
        $this->actingAs(User::factory()->create());

        $table = SellerResource::table(new Table());

        $this->assertNotNull($table->getColumns());
        $this->assertGreaterThan(0, count($table->getColumns()));
    }

    public function test_seller_resource_relations()
    {
        $relations = SellerResource::getRelations();

        $this->assertIsArray($relations);
    }

    public function test_seller_resource_pages()
    {
        $pages = SellerResource::getPages();

        $this->assertIsArray($pages);
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_seller_resource_filters()
    {
        $filters = SellerResource::getFilters();

        $this->assertIsArray($filters);
    }

    public function test_seller_resource_actions()
    {
        $actions = SellerResource::getActions();

        $this->assertIsArray($actions);
    }
}