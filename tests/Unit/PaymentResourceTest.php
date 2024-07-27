<?php

namespace Tests\Unit;

use App\Filament\Tenant\Resources\PaymentResource;
use App\Models\Payment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Forms\ComponentContainer;

class PaymentResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_resource_form()
    {
        $form = PaymentResource::form(new ComponentContainer());

        $this->assertNotNull($form->getSchema());
        $this->assertGreaterThan(0, count($form->getSchema()));
    }

    public function test_payment_resource_table()
    {
        $table = PaymentResource::table(new \Filament\Tables\Table());

        $this->assertNotNull($table->getColumns());
        $this->assertGreaterThan(0, count($table->getColumns()));
    }

    public function test_payment_resource_relations()
    {
        $relations = PaymentResource::getRelations();

        $this->assertIsArray($relations);
    }

    public function test_payment_resource_pages()
    {
        $pages = PaymentResource::getPages();

        $this->assertIsArray($pages);
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }
}