<?php

namespace Tests\Unit;

use App\Filament\Staff\Resources\Sellers\SellerResource;
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
        $this->markTestSkipped('Filament form tests require Livewire test setup.');
    }

    public function test_seller_resource_table()
    {
        $this->markTestSkipped('Filament table tests require Livewire test setup.');
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