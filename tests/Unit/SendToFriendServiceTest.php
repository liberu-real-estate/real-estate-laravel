<?php

namespace Tests\Unit;

use App\Services\SendToFriendService;
use App\Models\Property;
use App\Models\Team;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class SendToFriendServiceTest extends TestCase
{
    use RefreshDatabase;

    private SendToFriendService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SendToFriendService();
        Mail::fake();
    }

    /** @test */
    public function it_builds_email_data_for_property()
    {
        $team = Team::create(['name' => 'Test Team', 'user_id' => 1, 'personal_team' => false]);
        $user = User::factory()->create(['current_team_id' => $team->id]);
        $property = Property::factory()->create([
            'title' => 'Beautiful 3-bed house',
            'price' => 350000,
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        $data = $this->service->buildEmailData(
            $property,
            'Jane Doe',
            'John Smith',
            'john@example.com',
            'I thought you might love this place!'
        );

        $this->assertArrayHasKey('subject', $data);
        $this->assertArrayHasKey('body', $data);
        $this->assertArrayHasKey('property_url', $data);
        $this->assertStringContainsString('John Smith', $data['subject']);
        $this->assertStringContainsString('Jane Doe', $data['body']);
        $this->assertStringContainsString('Beautiful 3-bed house', $data['body']);
        $this->assertStringContainsString('I thought you might love this place!', $data['body']);
    }

    /** @test */
    public function it_throws_exception_for_invalid_recipient_email()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email address');

        $team = Team::create(['name' => 'Test Team', 'user_id' => 1, 'personal_team' => false]);
        $user = User::factory()->create(['current_team_id' => $team->id]);
        $property = Property::factory()->create(['user_id' => $user->id, 'team_id' => $team->id]);

        $this->service->sendPropertyToFriend(
            $property,
            'not-an-email',
            'Jane Doe',
            'John Smith',
            'john@example.com'
        );
    }

    /** @test */
    public function it_throws_exception_for_invalid_sender_email()
    {
        $this->expectException(\InvalidArgumentException::class);

        $team = Team::create(['name' => 'Test Team', 'user_id' => 1, 'personal_team' => false]);
        $user = User::factory()->create(['current_team_id' => $team->id]);
        $property = Property::factory()->create(['user_id' => $user->id, 'team_id' => $team->id]);

        $this->service->sendPropertyToFriend(
            $property,
            'jane@example.com',
            'Jane Doe',
            'John Smith',
            'invalid-email'
        );
    }

    /** @test */
    public function it_sends_property_to_friend()
    {
        $team = Team::create(['name' => 'Test Team', 'user_id' => 1, 'personal_team' => false]);
        $user = User::factory()->create(['current_team_id' => $team->id]);
        $property = Property::factory()->create(['user_id' => $user->id, 'team_id' => $team->id]);

        $result = $this->service->sendPropertyToFriend(
            $property,
            'jane@example.com',
            'Jane Doe',
            'John Smith',
            'john@example.com',
            'Check this out!'
        );

        $this->assertTrue($result);
        Mail::assertSent(function ($mail) {
            return true;
        });
    }

    /** @test */
    public function it_builds_email_without_personal_message()
    {
        $team = Team::create(['name' => 'Test Team', 'user_id' => 1, 'personal_team' => false]);
        $user = User::factory()->create(['current_team_id' => $team->id]);
        $property = Property::factory()->create(['user_id' => $user->id, 'team_id' => $team->id]);

        $data = $this->service->buildEmailData(
            $property,
            'Jane Doe',
            'John Smith',
            'john@example.com',
            null
        );

        $this->assertArrayHasKey('body', $data);
        $this->assertNull($data['personal_message']);
    }
}
