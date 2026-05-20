<?php

namespace Tests\Unit;

use App\Filament\Staff\Resources\Buyers\BuyerResource;
use App\Models\Buyer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Tables\Table;

class BuyerResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_buyer_resource_form()
    {
        $this->markTestSkipped('Filament form tests require Livewire test setup.');
    }

    public function test_buyer_resource_table()
    {
        $this->markTestSkipped('Filament table tests require Livewire test setup.');
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