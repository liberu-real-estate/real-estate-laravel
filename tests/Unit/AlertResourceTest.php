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
        // Create a user or an alert to act as a logged-in user
        $this->actingAs(User::factory()->create()); // Adjusted to use User model

        // Instantiate the form schema
        $form = AlertResource::form(new \Filament\Forms\Form());

        $this->assertNotNull($form->getSchema());
        $this->assertGreaterThan(0, count($form->getSchema()));
    }

    public function test_alert_resource_table()
    {
        $this->actingAs(User::factory()->create());

        // Instantiate the table schema
        $table = AlertResource::table(new \Filament\Tables\Table());

        $this->assertNotNull($table->getColumns());
        $this->assertGreaterThan(0, count($table->getColumns()));
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
        $filters = AlertResource::getFilters();

        $this->assertIsArray($filters);
    }

    public function test_alert_resource_widgets()
    {
        $widgets = AlertResource::getWidgets();

        $this->assertIsArray($widgets);
    }

    public function test_alert_resource_actions()
    {
        $actions = AlertResource::getActions();

        $this->assertIsArray($actions);
    }
}
