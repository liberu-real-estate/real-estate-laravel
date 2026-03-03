<?php

namespace Tests\Unit;

use App\Models\ViewingFeedback;
use App\Models\Appointment;
use App\Models\AppointmentType;
use App\Models\Property;
use App\Models\Team;
use App\Models\User;
use App\Services\ViewingFeedbackService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class ViewingFeedbackServiceTest extends TestCase
{
    use RefreshDatabase;

    private ViewingFeedbackService $service;
    private Property $property;
    private Appointment $appointment;
    private Team $team;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ViewingFeedbackService();
        Mail::fake();

        $this->team = Team::create(['name' => 'Test Team', 'user_id' => 1, 'personal_team' => false]);
        $this->user = User::factory()->create(['current_team_id' => $this->team->id]);
        $this->property = Property::factory()->create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
        ]);

        $appointmentType = AppointmentType::create(['name' => 'Viewing']);

        $this->appointment = Appointment::create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'agent_id' => $this->user->id,
            'appointment_date' => now()->addDay(),
            'status' => 'scheduled',
            'team_id' => $this->team->id,
            'appointment_type_id' => $appointmentType->id,
        ]);
    }

    /** @test */
    public function it_requests_viewing_feedback()
    {
        $feedback = $this->service->requestFeedback(
            $this->appointment,
            'jane@example.com',
            'Jane Doe'
        );

        $this->assertInstanceOf(ViewingFeedback::class, $feedback);
        $this->assertEquals($this->property->id, $feedback->property_id);
        $this->assertNotNull($feedback->token);
        $this->assertNotNull($feedback->feedback_requested_at);
        $this->assertDatabaseHas('viewing_feedbacks', ['property_id' => $this->property->id]);
    }

    /** @test */
    public function it_throws_exception_for_invalid_viewer_email()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid viewer email address');

        $this->service->requestFeedback($this->appointment, 'not-an-email', 'Jane Doe');
    }

    /** @test */
    public function it_submits_feedback()
    {
        $feedback = $this->service->requestFeedback(
            $this->appointment,
            'jane@example.com',
            'Jane Doe'
        );

        $submitted = $this->service->submitFeedback($feedback, [
            'overall_rating' => 4,
            'price_rating' => 3,
            'condition_rating' => 5,
            'location_rating' => 4,
            'size_rating' => 3,
            'interest_level' => 'interested',
            'positive_comments' => 'Great kitchen, nice garden.',
            'negative_comments' => 'Small bedrooms.',
            'would_make_offer' => true,
            'offer_price' => 340000,
        ]);

        $this->assertNotNull($submitted->feedback_submitted_at);
        $this->assertEquals(4, $submitted->overall_rating);
        $this->assertEquals('interested', $submitted->interest_level);
        $this->assertTrue($submitted->would_make_offer);
    }

    /** @test */
    public function it_throws_exception_when_submitting_twice()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Feedback has already been submitted');

        $feedback = $this->service->requestFeedback(
            $this->appointment,
            'jane@example.com',
            'Jane Doe'
        );

        $this->service->submitFeedback($feedback, ['overall_rating' => 4]);
        $this->service->submitFeedback($feedback->fresh(), ['overall_rating' => 5]);
    }

    /** @test */
    public function it_finds_feedback_by_token()
    {
        $feedback = $this->service->requestFeedback(
            $this->appointment,
            'jane@example.com',
            'Jane Doe'
        );

        $found = $this->service->findByToken($feedback->token);

        $this->assertNotNull($found);
        $this->assertEquals($feedback->id, $found->id);
    }

    /** @test */
    public function it_returns_null_for_invalid_token()
    {
        $found = $this->service->findByToken('nonexistent-token-xyz');
        $this->assertNull($found);
    }

    /** @test */
    public function it_generates_a_unique_token_automatically()
    {
        $feedback1 = $this->service->requestFeedback($this->appointment, 'a@example.com', 'A');

        $appointment2 = Appointment::create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'agent_id' => $this->user->id,
            'appointment_date' => now()->addDay(),
            'status' => 'scheduled',
            'team_id' => $this->team->id,
            'appointment_type_id' => $this->appointment->appointment_type_id,
        ]);
        $feedback2 = $this->service->requestFeedback($appointment2, 'b@example.com', 'B');

        $this->assertNotEquals($feedback1->token, $feedback2->token);
    }

    /** @test */
    public function it_gets_property_feedback_summary()
    {
        $feedback = $this->service->requestFeedback($this->appointment, 'jane@example.com', 'Jane');
        $this->service->submitFeedback($feedback, [
            'overall_rating' => 4,
            'interest_level' => 'very_interested',
            'would_make_offer' => true,
        ]);

        $appointment2 = Appointment::create([
            'property_id' => $this->property->id,
            'user_id' => $this->user->id,
            'agent_id' => $this->user->id,
            'appointment_date' => now()->addDay(),
            'status' => 'scheduled',
            'team_id' => $this->team->id,
            'appointment_type_id' => $this->appointment->appointment_type_id,
        ]);
        $feedback2 = $this->service->requestFeedback($appointment2, 'john@example.com', 'John');
        $this->service->submitFeedback($feedback2, [
            'overall_rating' => 3,
            'interest_level' => 'not_interested',
            'would_make_offer' => false,
        ]);

        $summary = $this->service->getPropertyFeedbackSummary($this->property);

        $this->assertEquals(2, $summary['total_viewings']);
        $this->assertEquals(3.5, $summary['average_overall_rating']);
        $this->assertEquals(1, $summary['would_make_offer_count']);
        $this->assertEquals(1, $summary['interested_viewers']);
    }

    /** @test */
    public function it_calculates_average_rating()
    {
        $feedback = ViewingFeedback::create([
            'property_id' => $this->property->id,
            'overall_rating' => 4,
            'price_rating' => 3,
            'condition_rating' => 5,
            'location_rating' => 4,
            'size_rating' => 3,
            'feedback_submitted_at' => now(),
        ]);

        $average = $feedback->getAverageRating();
        $this->assertEquals(3.8, $average);
    }
}
