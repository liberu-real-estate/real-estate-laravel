<?php

namespace Tests\Unit;

use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Forms\ComponentContainer;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_resource_form()
    {
        $this->actingAs(User::factory()->create());

        $form = UserResource::form(new ComponentContainer());

        $this->assertNotNull($form->getSchema());
        $this->assertCount(6, $form->getSchema());
    }

    public function test_user_resource_table()
    {
        $this->actingAs(User::factory()->create());

        $table = UserResource::table(new \Filament\Tables\Table());

        $this->assertNotNull($table->getColumns());
        $this->assertCount(6, $table->getColumns());
    }

    public function test_user_resource_relations()
    {
        $relations = UserResource::getRelations();

        $this->assertIsArray($relations);
    }

    public function test_user_resource_pages()
    {
        $pages = UserResource::getPages();

        $this->assertIsArray($pages);
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }
}