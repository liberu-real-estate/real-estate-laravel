<?php

namespace Tests\Unit;

use App\Models\HomeReport;
use App\Models\Property;
use App\Models\Team;
use App\Models\User;
use App\Services\HomeReportService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeReportServiceTest extends TestCase
{
    use RefreshDatabase;

    private HomeReportService $service;
    private Property $property;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new HomeReportService();

        $team = Team::create(['name' => 'Test Team', 'user_id' => 1, 'personal_team' => false]);
        $user = User::factory()->create(['current_team_id' => $team->id]);
        $this->property = Property::factory()->create([
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);
    }

    public function test_creates_a_home_report()
    {
        $report = $this->service->createHomeReport($this->property, [
            'surveyor_name' => 'John Surveyor',
            'surveyor_company' => 'ABC Surveys Ltd',
            'survey_date' => now()->toDateString(),
            'expiry_date' => now()->addYear()->toDateString(),
            'energy_band' => 'C',
            'energy_current_score' => 68,
            'energy_potential_score' => 81,
            'property_condition' => '1',
            'market_value' => 250000,
            'reinstatement_cost' => 180000,
        ]);

        $this->assertInstanceOf(HomeReport::class, $report);
        $this->assertEquals('C', $report->energy_band);
        $this->assertEquals('1', $report->property_condition);
        $this->assertDatabaseHas('home_reports', ['property_id' => $this->property->id]);
    }

    public function test_throws_exception_for_invalid_energy_band()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid energy band');

        $this->service->createHomeReport($this->property, [
            'energy_band' => 'Z',
            'property_condition' => '1',
        ]);
    }

    public function test_throws_exception_for_invalid_property_condition()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid property condition');

        $this->service->createHomeReport($this->property, [
            'energy_band' => 'C',
            'property_condition' => '5',
        ]);
    }

    public function test_detects_expired_report()
    {
        $report = $this->service->createHomeReport($this->property, [
            'survey_date' => now()->subYear()->toDateString(),
            'expiry_date' => now()->subMonth()->toDateString(),
        ]);

        $this->assertTrue($report->isExpired());
        $this->assertFalse($report->isValid());
    }

    public function test_detects_valid_report()
    {
        $report = $this->service->createHomeReport($this->property, [
            'survey_date' => now()->toDateString(),
            'expiry_date' => now()->addYear()->toDateString(),
        ]);

        $this->assertFalse($report->isExpired());
        $this->assertTrue($report->isValid());
    }

    public function test_updates_condition_ratings()
    {
        $report = $this->service->createHomeReport($this->property, []);

        $updated = $this->service->updateConditionRatings($report, [
            'structure' => '1',
            'roof_outside' => '2',
            'main_walls' => '3',
        ]);

        $this->assertEquals('1', $updated->condition_categories['structure']);
        $this->assertEquals('2', $updated->condition_categories['roof_outside']);
        $this->assertEquals('3', $updated->condition_categories['main_walls']);
    }

    public function test_throws_exception_for_invalid_condition_section()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid condition section');

        $report = $this->service->createHomeReport($this->property, []);
        $this->service->updateConditionRatings($report, ['invalid_section' => '1']);
    }

    public function test_gets_overall_condition_from_categories()
    {
        $report = $this->service->createHomeReport($this->property, ['property_condition' => '1']);
        $this->service->updateConditionRatings($report, ['structure' => '2', 'roof_outside' => '3']);

        $overall = $this->service->getOverallCondition($report->fresh());
        $this->assertEquals('3', $overall);
    }

    public function test_checks_if_property_has_valid_report()
    {
        $this->assertFalse($this->service->hasValidReport($this->property));

        $this->service->createHomeReport($this->property, [
            'survey_date' => now()->toDateString(),
            'expiry_date' => now()->addYear()->toDateString(),
        ]);

        $this->assertTrue($this->service->hasValidReport($this->property));
    }

    public function test_calculates_energy_improvement_points()
    {
        $report = $this->service->createHomeReport($this->property, [
            'energy_current_score' => 65,
            'energy_potential_score' => 80,
        ]);

        $this->assertEquals(15, $report->getEnergyImprovementPoints());
    }

    public function test_gets_latest_report_for_property()
    {
        $this->service->createHomeReport($this->property, [
            'survey_date' => now()->subYear()->toDateString(),
        ]);
        $latest = $this->service->createHomeReport($this->property, [
            'survey_date' => now()->toDateString(),
        ]);

        $found = $this->service->getLatestReport($this->property);
        $this->assertEquals($latest->id, $found->id);
    }
}
