# AI Chatbot Architecture Diagram

```
┌──────────────────────────────────────────────────────────────────────────┐
│                          USER INTERFACE LAYER                             │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│  ┌─────────────────────────────────────────────────────────────────┐    │
│  │  Chatbot Widget (Alpine.js)                                     │    │
│  │  - Fixed bottom-right position                                  │    │
│  │  - Toggle open/close                                           │    │
│  │  - Message input/display                                       │    │
│  │  - Typing indicator                                            │    │
│  │  - Escalation button                                           │    │
│  │  File: resources/views/components/chatbot-widget.blade.php    │    │
│  └─────────────────────────────────────────────────────────────────┘    │
│                                   │                                       │
│                                   │ AJAX Requests                        │
│                                   ▼                                       │
└──────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│                           API LAYER (REST)                                │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│  ┌─────────────────────────────────────────────────────────────────┐    │
│  │  ChatbotController                                              │    │
│  │  ┌───────────────────────────────────────────────────────────┐ │    │
│  │  │  POST   /api/chatbot/start                                │ │    │
│  │  │  POST   /api/chatbot/message                              │ │    │
│  │  │  GET    /api/chatbot/history/{sessionId}                  │ │    │
│  │  │  POST   /api/chatbot/escalate                             │ │    │
│  │  │  POST   /api/chatbot/close                                │ │    │
│  │  └───────────────────────────────────────────────────────────┘ │    │
│  │  File: app/Http/Controllers/ChatbotController.php             │    │
│  └─────────────────────────────────────────────────────────────────┘    │
│                                   │                                       │
│                                   │ Uses                                  │
│                                   ▼                                       │
└──────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│                          SERVICE LAYER (AI/NLP)                           │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│  ┌─────────────────────────────────────────────────────────────────┐    │
│  │  ChatbotService                                                 │    │
│  │  ┌───────────────────────────────────────────────────────────┐ │    │
│  │  │  processMessage()                                         │ │    │
│  │  │    ├─ detectIntent()      [Pattern Matching]             │ │    │
│  │  │    ├─ generateResponse()  [Contextual Responses]         │ │    │
│  │  │    └─ calculateConfidence() [Scoring]                    │ │    │
│  │  │                                                           │ │    │
│  │  │  Intent Detection:                                        │ │    │
│  │  │    • greeting                                             │ │    │
│  │  │    • property_search                                      │ │    │
│  │  │    • price_inquiry                                        │ │    │
│  │  │    • booking_inquiry                                      │ │    │
│  │  │    • contact_request                                      │ │    │
│  │  │    • location_inquiry                                     │ │    │
│  │  │    • features_inquiry                                     │ │    │
│  │  │    • general_inquiry                                      │ │    │
│  │  └───────────────────────────────────────────────────────────┘ │    │
│  │  File: app/Services/ChatbotService.php                       │    │
│  └─────────────────────────────────────────────────────────────────┘    │
│                                   │                                       │
│                                   │ Persists to                           │
│                                   ▼                                       │
└──────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│                           MODEL LAYER (ORM)                               │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│  ┌─────────────────────────────┐   ┌────────────────────────────────┐   │
│  │  ChatConversation Model     │   │  ChatMessage Model             │   │
│  │  ├─ user()                   │   │  ├─ conversation()             │   │
│  │  ├─ assignedAgent()          │   │  ├─ sender()                   │   │
│  │  ├─ messages()               │   │  ├─ isFromBot()                │   │
│  │  ├─ isEscalated()            │   │  ├─ isFromUser()               │   │
│  │  └─ escalate()               │   │  └─ isFromAgent()              │   │
│  │                              │   │                                 │   │
│  │  File: app/Models/          │   │  File: app/Models/              │   │
│  │  ChatConversation.php       │   │  ChatMessage.php                │   │
│  └─────────────────────────────┘   └────────────────────────────────┘   │
│                    │                              │                       │
│                    └──────────────┬───────────────┘                       │
│                                   │ Stores in                             │
│                                   ▼                                       │
└──────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│                         DATABASE LAYER (MySQL)                            │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│  ┌────────────────────────────────────────────────────────────────┐     │
│  │  chat_conversations                                            │     │
│  │  ├─ id (PK)                                                    │     │
│  │  ├─ user_id (FK → users)                                       │     │
│  │  ├─ session_id (unique)                                        │     │
│  │  ├─ status (active/escalated/closed)                           │     │
│  │  ├─ assigned_agent_id (FK → users)                             │     │
│  │  ├─ escalated_at                                               │     │
│  │  └─ timestamps                                                 │     │
│  └────────────────────────────────────────────────────────────────┘     │
│                                   │                                       │
│                                   │ 1:N                                   │
│                                   ▼                                       │
│  ┌────────────────────────────────────────────────────────────────┐     │
│  │  chat_messages                                                 │     │
│  │  ├─ id (PK)                                                    │     │
│  │  ├─ conversation_id (FK → chat_conversations)                  │     │
│  │  ├─ message (text)                                             │     │
│  │  ├─ sender_type (user/bot/agent)                               │     │
│  │  ├─ sender_id (FK → users, nullable)                           │     │
│  │  ├─ metadata (JSON: intent, confidence)                        │     │
│  │  └─ timestamps                                                 │     │
│  └────────────────────────────────────────────────────────────────┘     │
│                                                                           │
└──────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│                       ADMIN PANEL (Filament)                              │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│  ┌─────────────────────────────────────────────────────────────────┐    │
│  │  ChatConversationResource                                       │    │
│  │  ├─ Navigation: Support > Chat Conversations                    │    │
│  │  ├─ Badge: Count of escalated conversations                     │    │
│  │  │                                                               │    │
│  │  ├─ Pages:                                                       │    │
│  │  │   ├─ ListChatConversations (index)                          │    │
│  │  │   ├─ ViewChatConversation (view with messages)              │    │
│  │  │   └─ EditChatConversation (edit status, assign agent)       │    │
│  │  │                                                               │    │
│  │  ├─ Table Columns:                                              │    │
│  │  │   • ID, User, Status, Assigned Agent                         │    │
│  │  │   • Message Count, Escalated At, Started At                  │    │
│  │  │                                                               │    │
│  │  └─ Filters: By status (active/escalated/closed)               │    │
│  │                                                                  │    │
│  │  Files: app/Filament/Staff/Resources/ChatConversations/        │    │
│  └─────────────────────────────────────────────────────────────────┘    │
│                                                                           │
└──────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│                          WORKFLOW DIAGRAM                                 │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│  User Opens Chat                                                         │
│       │                                                                   │
│       ▼                                                                   │
│  POST /api/chatbot/start                                                 │
│       │                                                                   │
│       ├─→ Create ChatConversation                                        │
│       ├─→ Generate session_id                                            │
│       └─→ Return welcome message                                         │
│                                                                           │
│  User Sends Message                                                      │
│       │                                                                   │
│       ▼                                                                   │
│  POST /api/chatbot/message                                               │
│       │                                                                   │
│       ├─→ Save user message (ChatMessage)                                │
│       ├─→ ChatbotService.processMessage()                                │
│       │     ├─→ detectIntent()                                           │
│       │     ├─→ generateResponse()                                       │
│       │     └─→ calculateConfidence()                                    │
│       ├─→ Save bot response (ChatMessage)                                │
│       └─→ Return response with intent & confidence                       │
│                                                                           │
│  User Escalates (Optional)                                               │
│       │                                                                   │
│       ▼                                                                   │
│  POST /api/chatbot/escalate                                              │
│       │                                                                   │
│       ├─→ Update conversation.status = 'escalated'                       │
│       ├─→ Assign agent (if available)                                    │
│       ├─→ Set escalated_at timestamp                                     │
│       └─→ Notify agent (badge counter)                                   │
│                                                                           │
│  Agent Views in Admin Panel                                              │
│       │                                                                   │
│       ├─→ See escalated conversations (badge)                            │
│       ├─→ View full message history                                      │
│       ├─→ Update status / assign to self                                 │
│       └─→ Can close when resolved                                        │
│                                                                           │
└──────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│                        SECURITY MEASURES                                  │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│  ✓ CSRF Token Protection (all POST requests)                             │
│  ✓ Input Validation (Laravel Request Validation)                         │
│  ✓ SQL Injection Prevention (Eloquent ORM)                               │
│  ✓ XSS Prevention (Blade escaping)                                       │
│  ✓ Rate Limiting (Laravel Sanctum)                                       │
│  ✓ Authorization Checks (middleware)                                     │
│  ✓ Secure Session Management (UUID)                                      │
│                                                                           │
└──────────────────────────────────────────────────────────────────────────┘
```

## Key Features:

### 1. **Modular Architecture**
   - Clear separation of concerns
   - Each layer has specific responsibilities
   - Easy to maintain and extend

### 2. **Real-time Communication**
   - AJAX-based messaging
   - Instant response feedback
   - Typing indicators

### 3. **Intelligent Processing**
   - Pattern-based intent detection
   - Confidence scoring
   - Contextual responses

### 4. **Escalation Support**
   - Manual user escalation
   - Automatic low-confidence escalation
   - Agent assignment system
   - Admin dashboard management

### 5. **Data Persistence**
   - Full conversation history
   - Message metadata
   - Status tracking
   - User relationships

### 6. **Admin Management**
   - Filament integration
   - Badge notifications
   - Filtering and search
   - Detailed conversation views

## Technology Stack:

- **Frontend**: Alpine.js, Tailwind CSS, Blade Components
- **Backend**: Laravel 12, PHP 8.3
- **Database**: MySQL/PostgreSQL (via Eloquent)
- **Admin Panel**: Filament 5.0
- **Security**: Laravel Sanctum, CSRF Protection
- **Testing**: PHPUnit
