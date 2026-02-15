# AI Chatbot for Customer Support

## Overview

This feature implements an AI-powered chatbot to assist users with property inquiries and support. The chatbot uses natural language processing to understand user intent and provide relevant responses, with options for escalation to live agents when needed.

## Features

### 1. Intelligent Natural Language Processing
- **Intent Detection**: Automatically detects user intent from messages including:
  - Property searches
  - Price inquiries
  - Booking/viewing requests
  - Contact requests
  - Location inquiries
  - Features inquiries
  - General greetings

- **Confidence Scoring**: Each response includes a confidence score to help determine when escalation is needed

### 2. Chatbot UI Component
- **Floating Chat Widget**: Fixed position bottom-right chat interface
- **Real-time Messaging**: Instant message exchange with typing indicators
- **Message History**: Persistent conversation history
- **Responsive Design**: Mobile-friendly interface

### 3. Live Agent Escalation
- **Manual Escalation**: Users can request to speak with a live agent
- **Automatic Escalation**: Low-confidence responses suggest agent assistance
- **Agent Dashboard**: Filament admin panel for managing escalated conversations
- **Assignment System**: Conversations can be assigned to specific agents

### 4. Admin Management
- **Conversation Dashboard**: View and manage all chat conversations
- **Message History**: Full conversation view with message details
- **Status Management**: Track conversation status (active, escalated, closed)
- **Notification Badge**: Real-time count of escalated conversations

## Architecture

### Database Schema

#### chat_conversations Table
- `id`: Primary key
- `user_id`: Foreign key to users (nullable for guest users)
- `session_id`: Unique session identifier
- `status`: Conversation status (active, escalated, closed)
- `assigned_agent_id`: Foreign key to users (assigned agent)
- `escalated_at`: Timestamp of escalation
- `created_at`, `updated_at`: Timestamps

#### chat_messages Table
- `id`: Primary key
- `conversation_id`: Foreign key to chat_conversations
- `message`: Message text
- `sender_type`: Type of sender (user, bot, agent)
- `sender_id`: Foreign key to users (nullable)
- `metadata`: JSON field for intent, confidence, etc.
- `created_at`, `updated_at`: Timestamps

### Backend Components

#### Models
- **ChatConversation**: Manages conversation state and relationships
- **ChatMessage**: Stores individual messages

#### Services
- **ChatbotService**: Core AI processing service
  - `processMessage()`: Processes user input and generates responses
  - `detectIntent()`: Identifies user intent using pattern matching
  - `generateResponse()`: Creates contextual responses
  - `requiresEscalation()`: Determines if agent assistance is needed

#### Controllers
- **ChatbotController**: API endpoint handler
  - `POST /api/chatbot/start`: Initialize conversation
  - `POST /api/chatbot/message`: Send message
  - `GET /api/chatbot/history/{sessionId}`: Get conversation history
  - `POST /api/chatbot/escalate`: Escalate to agent
  - `POST /api/chatbot/close`: Close conversation

### Frontend Components

#### Chatbot Widget (Alpine.js)
- **Location**: `resources/views/components/chatbot-widget.blade.php`
- **Features**:
  - Toggle chat window
  - Send and receive messages
  - Typing indicator
  - Escalation button
  - Automatic scrolling
  - Time formatting

### Admin Panel (Filament)

#### ChatConversationResource
- **Location**: `app/Filament/Staff/Resources/ChatConversations/`
- **Features**:
  - List all conversations
  - View conversation details and messages
  - Edit conversation status
  - Assign agents
  - Filter by status
  - Badge counter for escalated conversations

## Installation

### 1. Run Migrations
```bash
php artisan migrate
```

This creates the `chat_conversations` and `chat_messages` tables.

### 2. Add Widget to Layout
The chatbot widget is automatically included in `resources/views/layouts/app.blade.php`.

### 3. Clear Cache
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Usage

### For End Users

1. **Starting a Conversation**
   - Click the chat icon in the bottom-right corner
   - The chatbot will greet you and offer assistance

2. **Asking Questions**
   - Type your question or request in the input field
   - Press Enter or click the send button
   - The chatbot will respond based on detected intent

3. **Escalating to Agent**
   - Click "Talk to a live agent" if available
   - Or ask to speak with an agent in your message
   - An agent will be notified of your request

### For Agents/Admins

1. **Accessing Conversations**
   - Log in to the admin panel
   - Navigate to Support > Chat Conversations
   - Badge shows count of escalated conversations

2. **Viewing Conversations**
   - Click on a conversation to view details
   - See full message history
   - Check conversation status and metadata

3. **Managing Conversations**
   - Assign conversations to specific agents
   - Update conversation status
   - Close resolved conversations

## API Endpoints

### Start Conversation
```
POST /api/chatbot/start
Response: { conversation_id, session_id, message }
```

### Send Message
```
POST /api/chatbot/message
Body: { session_id, message }
Response: { message, intent, confidence, suggest_escalation }
```

### Get History
```
GET /api/chatbot/history/{sessionId}
Response: { messages: [], status }
```

### Escalate Conversation
```
POST /api/chatbot/escalate
Body: { session_id, reason? }
Response: { message, success }
```

### Close Conversation
```
POST /api/chatbot/close
Body: { session_id }
Response: { message, success }
```

## Configuration

### Customizing Responses

Edit `app/Services/ChatbotService.php` to:
- Add new intent patterns
- Customize response messages
- Adjust confidence thresholds
- Add new intents

### Styling the Widget

Modify `resources/views/components/chatbot-widget.blade.php` to:
- Change colors and styling
- Adjust positioning
- Customize animations
- Update button text

## Testing

Run the chatbot tests:
```bash
php artisan test --filter=ChatbotTest
```

Tests cover:
- Conversation creation
- Message creation
- Intent detection
- Escalation functionality
- Message type identification
- Relationships

## Future Enhancements

Potential improvements:
- Integration with OpenAI or other AI services for more advanced NLP
- Real-time notifications for agents
- Chat history for logged-in users
- Multilingual support
- Sentiment analysis
- Proactive chat triggers
- Chat analytics and reporting
- File attachments support

## Troubleshooting

### Chatbot Not Appearing
- Check that the widget is included in your layout
- Verify Alpine.js is loaded
- Check browser console for JavaScript errors

### Messages Not Sending
- Verify CSRF token is present
- Check API routes are registered
- Ensure database tables exist

### Escalation Not Working
- Verify users with admin/staff roles exist
- Check agent assignment logic
- Review conversation status

## Security Considerations

- CSRF protection enabled on all endpoints
- Input validation on all user messages
- SQL injection protection via Eloquent ORM
- XSS prevention in message display
- Rate limiting on API endpoints (via Laravel Sanctum)

## Support

For issues or questions:
- Check the logs in `storage/logs/laravel.log`
- Review the admin panel for conversation details
- Check the database for data integrity
