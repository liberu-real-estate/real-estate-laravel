<?php

namespace Tests\Unit;

use App\Models\SalesProgression;
use App\Models\Property;
use App\Models\Team;
use App\Models\User;
use App\Services\SalesProgressionService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SalesProgressionServiceTest extends TestCase
{
    use RefreshDatabase;

    private SalesProgressionService $service;
    private Property $property;
    private Team $team;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SalesProgressionService();

        $this->team = Team::create(['name' => 'Test Team', 'user_id' => 1, 'personal_team' => false]);
        $this->user = User::factory()->create(['current_team_id' => $this->team->id]);
        $this->property = Property::factory()->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);
    }

    public function test_creates_a_sales_progression_with_default_stage()
    {
        $progression = $this->service->createProgression($this->property, [
            'team_id' => $this->team->id,
            'sale_price' => 300000,
        ]);

        $this->assertInstanceOf(SalesProgression::class, $progression);
        $this->assertEquals('offer_accepted', $progression->stage);
        $this->assertNotEmpty($progression->checklist_items);
        $this->assertDatabaseHas('sales_progressions', ['property_id' => $this->property->id]);
    }

    public function test_advances_to_next_stage()
    {
        $progression = $this->service->createProgression($this->property);

        $this->assertEquals('offer_accepted', $progression->stage);

        $advanced = $this->service->advanceStage($progression);

        $this->assertEquals('solicitors_instructed', $advanced->stage);
    }

    public function test_throws_exception_when_advancing_from_final_stage()
    {
        $progression = $this->service->createProgression($this->property, ['stage' => 'completed']);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot advance: already at the final stage');

        $this->service->advanceStage($progression);
    }

    public function test_updates_stage_manually()
    {
        $progression = $this->service->createProgression($this->property);

        $updated = $this->service->updateStage($progression, 'exchanged');

        $this->assertEquals('exchanged', $updated->stage);
    }

    public function test_throws_exception_for_invalid_stage()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid stage');

        $progression = $this->service->createProgression($this->property);
        $this->service->updateStage($progression, 'flying_on_a_rocket');
    }

    public function test_updates_checklist_item()
    {
        $progression = $this->service->createProgression($this->property);

        $updated = $this->service->updateChecklistItem($progression, 'offer_agreed', true);

        $checklist = $updated->checklist_items;
        $item = collect($checklist)->firstWhere('key', 'offer_agreed');
        $this->assertTrue($item['completed']);
    }

    public function test_throws_exception_for_invalid_checklist_key()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Checklist item 'nonexistent_key' not found");

        $progression = $this->service->createProgression($this->property);
        $this->service->updateChecklistItem($progression, 'nonexistent_key', true);
    }

    public function test_calculates_checklist_completion_percentage()
    {
        $progression = $this->service->createProgression($this->property);

        $percentage = $this->service->getChecklistCompletionPercentage($progression);
        $this->assertEquals(0, $percentage);

        // Complete 4 items out of 20 = 20%
        foreach (['offer_agreed', 'memorandum_sent', 'solicitors_instructed', 'id_checks'] as $key) {
            $progression = $this->service->updateChecklistItem($progression, $key, true);
        }

        $percentage = $this->service->getChecklistCompletionPercentage($progression->fresh());
        $this->assertEquals(20, $percentage);
    }

    public function test_calculates_stage_progress_percentage()
    {
        $progression = $this->service->createProgression($this->property, ['stage' => 'offer_accepted']);
        $this->assertEquals(9, $progression->getStageProgressPercentage());

        $updated = $this->service->updateStage($progression, 'completed');
        $this->assertEquals(100, $updated->getStageProgressPercentage());
    }

    public function test_identifies_completed_progression()
    {
        $active = $this->service->createProgression($this->property);
        $this->assertFalse($active->isCompleted());

        $completed = $this->service->updateStage($active, 'completed');
        $this->assertTrue($completed->isCompleted());
    }

    public function test_returns_stage_label()
    {
        $progression = $this->service->createProgression($this->property, ['stage' => 'exchanged']);
        $this->assertEquals('Exchanged', $progression->stage_label);
    }

    public function test_scopes_active_progressions()
    {
        $this->service->createProgression($this->property, ['stage' => 'offer_accepted']);

        $property2 = Property::factory()->create(['user_id' => $this->user->id, 'team_id' => $this->team->id]);
        $this->service->createProgression($property2, ['stage' => 'completed']);

        $active = SalesProgression::active()->get();
        $this->assertCount(1, $active);
        $this->assertEquals('offer_accepted', $active->first()->stage);
    }
}
