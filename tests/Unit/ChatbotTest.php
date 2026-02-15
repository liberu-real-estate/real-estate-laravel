<?php

namespace Tests\Unit;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use App\Services\ChatbotService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    protected $chatbotService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->chatbotService = new ChatbotService();
    }

    /** @test */
    public function it_can_create_a_chat_conversation()
    {
        $user = User::factory()->create();
        
        $conversation = ChatConversation::create([
            'user_id' => $user->id,
            'session_id' => 'test-session-123',
            'status' => 'active',
        ]);

        $this->assertInstanceOf(ChatConversation::class, $conversation);
        $this->assertEquals('test-session-123', $conversation->session_id);
        $this->assertEquals('active', $conversation->status);
    }

    /** @test */
    public function it_can_create_chat_messages()
    {
        $user = User::factory()->create();
        $conversation = ChatConversation::create([
            'user_id' => $user->id,
            'session_id' => 'test-session-123',
            'status' => 'active',
        ]);

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'message' => 'Hello, I need help',
            'sender_type' => 'user',
            'sender_id' => $user->id,
        ]);

        $this->assertInstanceOf(ChatMessage::class, $message);
        $this->assertEquals('Hello, I need help', $message->message);
        $this->assertEquals('user', $message->sender_type);
    }

    /** @test */
    public function it_detects_greeting_intent()
    {
        $response = $this->chatbotService->processMessage('Hello!');
        
        $this->assertEquals('greeting', $response['intent']);
        $this->assertStringContainsString('Welcome', $response['message']);
    }

    /** @test */
    public function it_detects_property_search_intent()
    {
        $response = $this->chatbotService->processMessage('I am looking for a property');
        
        $this->assertEquals('property_search', $response['intent']);
        $this->assertGreaterThan(0, $response['confidence']);
    }

    /** @test */
    public function it_detects_price_inquiry_intent()
    {
        $response = $this->chatbotService->processMessage('How much does it cost?');
        
        $this->assertEquals('price_inquiry', $response['intent']);
    }

    /** @test */
    public function it_can_escalate_conversation()
    {
        $user = User::factory()->create();
        $conversation = ChatConversation::create([
            'user_id' => $user->id,
            'session_id' => 'test-session-123',
            'status' => 'active',
        ]);

        $this->assertFalse($conversation->isEscalated());
        
        $conversation->escalate();
        $conversation->refresh();

        $this->assertEquals('escalated', $conversation->status);
        $this->assertTrue($conversation->isEscalated());
        $this->assertNotNull($conversation->escalated_at);
    }

    /** @test */
    public function it_identifies_messages_by_sender_type()
    {
        $user = User::factory()->create();
        $conversation = ChatConversation::create([
            'user_id' => $user->id,
            'session_id' => 'test-session-123',
            'status' => 'active',
        ]);

        $userMessage = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'message' => 'User message',
            'sender_type' => 'user',
            'sender_id' => $user->id,
        ]);

        $botMessage = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'message' => 'Bot response',
            'sender_type' => 'bot',
        ]);

        $this->assertTrue($userMessage->isFromUser());
        $this->assertFalse($userMessage->isFromBot());
        
        $this->assertTrue($botMessage->isFromBot());
        $this->assertFalse($botMessage->isFromUser());
    }

    /** @test */
    public function conversation_has_messages_relationship()
    {
        $user = User::factory()->create();
        $conversation = ChatConversation::create([
            'user_id' => $user->id,
            'session_id' => 'test-session-123',
            'status' => 'active',
        ]);

        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'message' => 'First message',
            'sender_type' => 'user',
        ]);

        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'message' => 'Second message',
            'sender_type' => 'bot',
        ]);

        $this->assertCount(2, $conversation->messages);
    }
}
