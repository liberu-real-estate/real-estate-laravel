<?php

namespace Tests\Unit;

use App\Models\Message;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_message()
    {
        $messageData = [
            'content' => 'Test message content',
            'sender_id' => User::factory()->create()->id,
            'receiver_id' => User::factory()->create()->id,
        ];

        $message = Message::create($messageData);

        $this->assertInstanceOf(Message::class, $message);
        $this->assertDatabaseHas('messages', $messageData);
    }

    public function test_message_relationships()
    {
        $message = Message::factory()->create();

        $this->assertInstanceOf(User::class, $message->sender);
        $this->assertInstanceOf(User::class, $message->receiver);
    }

    public function test_message_scopes()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Message::factory()->count(3)->create(['sender_id' => $user1->id, 'receiver_id' => $user2->id]);
        Message::factory()->count(2)->create(['sender_id' => $user2->id, 'receiver_id' => $user1->id]);

        $this->assertCount(5, Message::betweenUsers($user1->id, $user2->id)->get());
    }
}