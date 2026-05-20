# AI Chatbot UI Design Specification

## Overview
The chatbot UI is designed to be user-friendly, modern, and accessible, seamlessly integrating with the existing real estate application.

## UI Components

### 1. Chat Toggle Button

**Location**: Fixed position at bottom-right of screen (right: 1rem, bottom: 1rem)

**Design**:
- Shape: Circular button
- Size: 56px × 56px (p-4 with icon)
- Color: Blue 600 (#2563eb)
- Hover: Blue 700 (#1d4ed8)
- Icon: Chat bubble with text lines (Heroicons)
- Shadow: Large shadow for elevation
- Animation: Smooth scale transition

**Behavior**:
- Visible when chat is closed
- Hidden when chat is open
- Click to open chat window
- Hover effect (darker blue)

### 2. Chat Window

**Dimensions**:
- Width: 384px (w-96)
- Height: 600px (h-[600px])
- Position: Fixed bottom-right
- Border Radius: 8px (rounded-lg)
- Shadow: 2xl shadow for depth

**Sections**:

#### A. Header
**Design**:
- Background: Blue 600 (#2563eb)
- Text: White
- Height: Auto (p-4)
- Border Radius: Top corners rounded (rounded-t-lg)

**Content**:
- Left side:
  - Green status indicator (3px circle, green-400)
  - Title: "Customer Support" (font-semibold)
- Right side:
  - Close button (X icon)
  - Hover: Slight opacity change

#### B. Messages Container
**Design**:
- Flex: 1 (takes remaining space)
- Overflow: Auto scroll
- Padding: 1rem (p-4)
- Background: White
- Spacing: Gap of 1rem between messages (space-y-4)

**Message Bubbles**:

**User Messages** (Right-aligned):
- Background: Blue 600 (#2563eb)
- Text: White
- Border Radius: 8px (rounded-lg)
- Padding: 0.75rem (p-3)
- Max Width: 80%
- Shadow: Small shadow
- Alignment: Right-justified (flex justify-end)

**Bot Messages** (Left-aligned):
- Background: Gray 100 (#f3f4f6)
- Text: Gray 800 (#1f2937)
- Border Radius: 8px (rounded-lg)
- Padding: 0.75rem (p-3)
- Max Width: 80%
- Shadow: Small shadow
- Alignment: Left-justified (flex justify-start)

**Agent Messages** (Left-aligned):
- Background: Green 100 (#dcfce7)
- Text: Gray 800 (#1f2937)
- Border Radius: 8px (rounded-lg)
- Padding: 0.75rem (p-3)
- Max Width: 80%
- Shadow: Small shadow
- Label: "Agent" badge (green-700, font-semibold)
- Alignment: Left-justified (flex justify-start)

**Message Metadata**:
- Timestamp: Small text (text-xs)
- Opacity: 70% (opacity-70)
- Position: Below message text
- Format: "12:30 PM" format

**Typing Indicator**:
- Position: Left-aligned (like bot message)
- Background: Gray 100
- Animation: 3 bouncing dots
- Dot Color: Gray 400
- Dot Size: 2px circles (w-2 h-2)
- Animation Delay: Staggered (0s, 0.1s, 0.2s)

#### C. Escalation Option Bar (Conditional)
**Design**:
- Background: Yellow 50 (#fefce8)
- Border: Top border, yellow 200
- Padding: 0.5rem 1rem (px-4 py-2)
- Text: Blue 600 with hover blue 800

**Content**:
- Icon: User profile icon (Heroicons)
- Text: "Talk to a live agent"
- Hover: Underline and darker color

**Visibility**:
- Shows when `showEscalationOption` is true
- Hides when conversation is escalated
- Appears after low-confidence responses

#### D. Escalated Notice (Conditional)
**Design**:
- Background: Green 50 (#f0fdf4)
- Border: Top border, green 200
- Padding: 0.5rem 1rem (px-4 py-2)
- Text: Green 700

**Content**:
- Text: "Connected to a live agent"
- Icon: Optional checkmark

**Visibility**:
- Shows only when conversation status is 'escalated'
- Replaces escalation option bar

#### E. Input Area
**Design**:
- Background: White
- Border: Top border, gray 200
- Padding: 1rem (p-4)

**Components**:

**Text Input**:
- Type: Text input field
- Border: Gray 300
- Border Radius: 8px (rounded-lg)
- Padding: 0.5rem 1rem (px-4 py-2)
- Placeholder: "Type your message..."
- Focus: Blue ring (focus:ring-2 focus:ring-blue-600)
- Disabled State: Gray background when escalated

**Send Button**:
- Background: Blue 600
- Hover: Blue 700
- Border Radius: 8px (rounded-lg)
- Padding: 0.5rem 1rem (px-4 py-2)
- Icon: Paper airplane/send arrow (Heroicons)
- Disabled State: 50% opacity, no pointer
- Transition: Smooth color transition

## Animations

### 1. Chat Window Open/Close
- **Type**: Scale and opacity transition
- **Duration**: 200ms (enter), 150ms (leave)
- **Enter**: opacity-0 scale-95 → opacity-100 scale-100
- **Leave**: opacity-100 scale-100 → opacity-0 scale-95
- **Easing**: ease-out (enter), ease-in (leave)

### 2. Typing Indicator
- **Type**: Bounce animation
- **Duration**: Infinite
- **Effect**: Vertical bounce
- **Stagger**: 0.1s delay between dots

### 3. Message Appear
- **Type**: Fade and slide
- **Effect**: Smooth addition to message list
- **Auto-scroll**: Smooth scroll to bottom

### 4. Hover Effects
- **Buttons**: Color change (200ms transition)
- **Close button**: Opacity change
- **Escalate link**: Underline and color change

## Color Palette

```
Primary (Blue):
- bg-blue-600: #2563eb (main background)
- bg-blue-700: #1d4ed8 (hover state)
- text-blue-600: #2563eb (links)
- text-blue-800: #1e40af (link hover)

Success (Green):
- bg-green-50: #f0fdf4 (escalated notice bg)
- bg-green-100: #dcfce7 (agent message bg)
- bg-green-400: #4ade80 (status indicator)
- text-green-700: #15803d (agent label)

Warning (Yellow):
- bg-yellow-50: #fefce8 (escalation option bg)
- border-yellow-200: #fef08a (escalation border)

Neutral (Gray):
- bg-gray-100: #f3f4f6 (bot message bg, input disabled)
- bg-gray-400: #9ca3af (typing dots)
- text-gray-800: #1f2937 (message text)
- border-gray-200: #e5e7eb (dividers)
- border-gray-300: #d1d5db (input border)

Background:
- bg-white: #ffffff (main chat bg, input bg)
```

## Typography

```
Font Family: sans-serif (system default)
Font Sizes:
- text-2xl: 1.5rem (not used in current design)
- text-sm: 0.875rem (message text)
- text-xs: 0.75rem (timestamps, labels)

Font Weights:
- font-normal: 400 (regular text)
- font-semibold: 600 (titles, labels)

Line Height: Default Tailwind
```

## Spacing

```
Padding:
- p-4: 1rem (general padding)
- p-3: 0.75rem (message bubbles)
- py-2 px-4: Vertical 0.5rem, Horizontal 1rem (buttons, bars)

Margin/Gap:
- space-y-4: 1rem vertical gap between messages
- gap-2: 0.5rem gap in input form
- gap-1: 0.25rem gap in escalate button

Border Radius:
- rounded-full: 9999px (toggle button, status indicator)
- rounded-lg: 0.5rem (chat window, bubbles, inputs)
- rounded-t-lg: 0.5rem top only (header)

Shadows:
- shadow-lg: Large shadow (toggle button)
- shadow-2xl: Extra large shadow (chat window)
- shadow-sm: Small shadow (message bubbles)
```

## Responsive Behavior

### Desktop (Default)
- Width: 384px fixed
- Height: 600px fixed
- Position: Bottom-right corner

### Tablet (768px - 1024px)
- Same as desktop
- Adequate spacing from edges

### Mobile (< 768px)
**Recommended Enhancement**:
- Full screen on mobile (width: 100vw, height: 100vh)
- Remove border radius
- Position: Fixed full screen
- Z-index: High to overlay everything

## Accessibility Features

### Keyboard Navigation
- Tab through interactive elements
- Enter to send message
- Escape to close chat

### ARIA Labels
- Chat button: `aria-label="Open chat"`
- Close button: `aria-label="Close chat"`
- Input field: Proper label association

### Screen Reader Support
- Semantic HTML structure
- Proper heading hierarchy
- Form labels
- Button descriptions

### Visual Accessibility
- Sufficient color contrast (WCAG AA compliant)
- Focus indicators (blue ring)
- Clear button states
- Readable font sizes

## User Flow Visualization

```
┌────────────────────────────────────────┐
│  [Page Content]                        │
│                                        │
│                                        │
│                          ┌───────────┐│
│                          │  ○ Chat   ││  ← Toggle Button
│                          └───────────┘│
└────────────────────────────────────────┘

                    ▼ Click

┌────────────────────────────────────────┐
│  [Page Content]                        │
│                                        │
│  ┌──────────────────────────────────┐ │
│  │ ● Customer Support          [X] │ │  ← Header
│  ├──────────────────────────────────┤ │
│  │ Bot: Welcome! How can I help?   │ │
│  │                          12:30PM │ │
│  │                                  │ │  ← Messages
│  │              You: Hi, looking   │ │
│  │              for a property     │ │
│  │                          12:31PM │ │
│  │                                  │ │
│  │ Bot: I can help you find...     │ │
│  │                          12:31PM │ │
│  ├──────────────────────────────────┤ │
│  │ 👤 Talk to a live agent         │ │  ← Escalation (Optional)
│  ├──────────────────────────────────┤ │
│  │ [Type your message...] [Send >] │ │  ← Input
│  └──────────────────────────────────┘ │
└────────────────────────────────────────┘

                    ▼ Escalate

┌────────────────────────────────────────┐
│  [Page Content]                        │
│                                        │
│  ┌──────────────────────────────────┐ │
│  │ ● Customer Support          [X] │ │
│  ├──────────────────────────────────┤ │
│  │ [Previous messages...]           │ │
│  │                                  │ │
│  │ Bot: Your conversation has been │ │
│  │ escalated to a live agent...    │ │
│  │                          12:32PM │ │
│  ├──────────────────────────────────┤ │
│  │ ✓ Connected to a live agent     │ │  ← Status Notice
│  ├──────────────────────────────────┤ │
│  │ [Type disabled - waiting...]    │ │  ← Disabled Input
│  └──────────────────────────────────┘ │
└────────────────────────────────────────┘
```

## Browser Compatibility

- Chrome: ✓ Full support
- Firefox: ✓ Full support
- Safari: ✓ Full support
- Edge: ✓ Full support
- Mobile Safari: ✓ Full support
- Mobile Chrome: ✓ Full support

## Performance Considerations

- Lazy load: Chat initializes on first open
- Message limit: Consider pagination for very long conversations
- Debounce: Input typing events (if real-time typing indicator added)
- Efficient re-renders: Alpine.js reactive data binding

## Future UI Enhancements

1. **Rich Media Support**: Display images, links with previews
2. **Quick Replies**: Suggested response buttons
3. **Emojis**: Emoji picker in input
4. **File Upload**: Attach documents/images
5. **Minimize**: Minimize to badge instead of closing
6. **Sound**: Optional notification sounds
7. **Dark Mode**: Theme toggle
8. **Chat History**: Expandable past conversations
9. **Proactive**: Auto-open with contextual messages
10. **Rating**: End-of-chat satisfaction rating
