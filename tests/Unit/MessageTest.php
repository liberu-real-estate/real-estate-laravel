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
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $messageData = [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'content' => 'Test message content',
            'read_at' => null,
        ];

        $message = Message::create($messageData);

        $this->assertInstanceOf(Message::class, $message);
        $this->assertDatabaseHas('messages', ['id' => $message->id]);
    }

    public function test_message_relationships()
    {
        $message = Message::factory()->create();

        $this->assertInstanceOf(User::class, $message->sender);
        $this->assertInstanceOf(User::class, $message->recipient);
    }

    public function test_message_scope()
    {
        $user = User::factory()->create();
        Message::factory()->count(3)->create(['recipient_id' => $user->id]);
        Message::factory()->count(2)->create(['sender_id' => $user->id]);

        $receivedMessages = Message::receivedBy($user->id)->get();
        $sentMessages = Message::sentBy($user->id)->get();

        $this->assertCount(3, $receivedMessages);
        $this->assertCount(2, $sentMessages);
    }

    public function test_mark_as_read()
    {
        $message = Message::factory()->create(['read_at' => null]);

        $message->markAsRead();

        $this->assertNotNull($message->read_at);
    }

    public function test_message_content_max_length()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Message::create([
            'content' => str_repeat('a', 1001), // Assuming max length is 1000
            'sender_id' => 1,
            'receiver_id' => 2,
        ]);
    }

    public function test_message_soft_delete()
    {
        $message = Message::factory()->create();
        $message->delete();

        $this->assertSoftDeleted($message);
    }

    public function test_message_content_max_length()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Message::create([
            'content' => str_repeat('a', 1001), // Assuming max length is 1000
            'sender_id' => 1,
            'receiver_id' => 2,
        ]);
    }

    public function test_message_soft_delete()
    {
        $message = Message::factory()->create();
        $message->delete();

        $this->assertSoftDeleted($message);
    }
}