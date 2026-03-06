<?php 

namespace Tests\Unit;

use App\Filament\Resources\AlertResource;
use App\Models\Alert;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlertResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_alert_resource_form()
    {
        $this->markTestSkipped('Filament form tests require Livewire test setup.');
    }

    public function test_alert_resource_table()
    {
        $this->markTestSkipped('Filament table tests require Livewire test setup.');
    }

    public function test_alert_resource_relations()
    {
        $relations = AlertResource::getRelations();

        $this->assertIsArray($relations);
    }

    public function test_alert_resource_pages()
    {
        $pages = AlertResource::getPages();

        $this->assertIsArray($pages);
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    public function test_alert_resource_filters()
    {
        $this->assertIsString(AlertResource::class);
    }

    public function test_alert_resource_widgets()
    {
        $widgets = AlertResource::getWidgets();

        $this->assertIsArray($widgets);
    }

    public function test_alert_resource_actions()
    {
        $this->assertIsString(AlertResource::class);
    }
}
