<?php

namespace App\Services;

use App\Models\Property;
use App\Models\News;

class ChatbotService
{
    /**
     * Process user message and generate response.
     */
    public function processMessage(string $message): array
    {
        $message = strtolower(trim($message));
        
        // Detect intent and generate response
        $intent = $this->detectIntent($message);
        $response = $this->generateResponse($intent, $message);
        
        return [
            'message' => $response,
            'intent' => $intent,
            'confidence' => $this->calculateConfidence($intent, $message),
        ];
    }

    /**
     * Detect user intent from message.
     */
    private function detectIntent(string $message): string
    {
        // Property search related
        if (preg_match('/\b(looking for|find|search|buy|rent|property|house|apartment|properties)\b/i', $message)) {
            return 'property_search';
        }
        
        // Price inquiries
        if (preg_match('/\b(price|cost|how much|pricing|budget)\b/i', $message)) {
            return 'price_inquiry';
        }
        
        // Booking/viewing
        if (preg_match('/\b(book|schedule|viewing|visit|appointment|tour)\b/i', $message)) {
            return 'booking_inquiry';
        }
        
        // Contact/support
        if (preg_match('/\b(contact|speak|talk|agent|help|support)\b/i', $message)) {
            return 'contact_request';
        }
        
        // Greetings
        if (preg_match('/\b(hello|hi|hey|good morning|good afternoon|good evening)\b/i', $message)) {
            return 'greeting';
        }
        
        // Location inquiries
        if (preg_match('/\b(where|location|area|neighborhood|address)\b/i', $message)) {
            return 'location_inquiry';
        }
        
        // Features inquiries
        if (preg_match('/\b(bedroom|bathroom|parking|garden|amenities|features)\b/i', $message)) {
            return 'features_inquiry';
        }
        
        return 'general_inquiry';
    }

    /**
     * Generate response based on intent.
     */
    private function generateResponse(string $intent, string $message): string
    {
        switch ($intent) {
            case 'greeting':
                return "Hello! Welcome to our real estate platform. I'm here to help you find your perfect property. How can I assist you today?";
                
            case 'property_search':
                $properties = Property::where('status', 'available')->take(3)->get();
                if ($properties->isEmpty()) {
                    return "I'd be happy to help you find properties! Currently, we're updating our listings. Please check back soon or contact our team for the latest available properties.";
                }
                
                $response = "I can help you find properties! Here are some of our latest listings:\n\n";
                foreach ($properties as $property) {
                    $response .= "• {$property->title} - £" . number_format($property->price, 2) . "\n";
                }
                $response .= "\nWould you like to know more about any of these, or shall I help you with specific requirements?";
                return $response;
                
            case 'price_inquiry':
                return "Our properties range from various price points to suit different budgets. Could you tell me your budget range or the type of property you're interested in? This will help me provide more accurate information.";
                
            case 'booking_inquiry':
                return "I can help you schedule a property viewing! To book a viewing, please:\n1. Browse our available properties\n2. Click on the property you're interested in\n3. Use the 'Book a Viewing' button\n\nAlternatively, would you like me to connect you with one of our agents who can arrange this for you?";
                
            case 'contact_request':
                return "I'd be happy to connect you with one of our agents! You can:\n• Click the 'Escalate to Agent' button for immediate assistance\n• Call us at our office\n• Fill out our contact form\n\nWould you like me to escalate this conversation to a live agent now?";
                
            case 'location_inquiry':
                return "We have properties in various locations. Could you tell me which area or neighborhood you're interested in? This will help me find the most suitable properties for you.";
                
            case 'features_inquiry':
                return "I can help you find properties with specific features! Please let me know what you're looking for, such as:\n• Number of bedrooms\n• Bathrooms\n• Parking spaces\n• Garden\n• Other amenities\n\nWhat features are important to you?";
                
            case 'general_inquiry':
            default:
                return "I'm here to help! I can assist you with:\n• Finding properties for sale or rent\n• Scheduling viewings\n• Answering questions about our services\n• Connecting you with our agents\n\nWhat would you like to know more about?";
        }
    }

    /**
     * Calculate confidence score for intent detection.
     */
    private function calculateConfidence(string $intent, string $message): float
    {
        // Simple confidence calculation based on keyword matches
        $keywords = $this->getIntentKeywords($intent);
        $matches = 0;
        
        foreach ($keywords as $keyword) {
            if (stripos($message, $keyword) !== false) {
                $matches++;
            }
        }
        
        return min(1.0, ($matches / max(1, count($keywords))) + 0.3);
    }

    /**
     * Get keywords for intent.
     */
    private function getIntentKeywords(string $intent): array
    {
        return match($intent) {
            'greeting' => ['hello', 'hi', 'hey'],
            'property_search' => ['property', 'house', 'apartment', 'find', 'search'],
            'price_inquiry' => ['price', 'cost', 'budget'],
            'booking_inquiry' => ['book', 'viewing', 'schedule'],
            'contact_request' => ['contact', 'agent', 'help'],
            'location_inquiry' => ['location', 'area', 'where'],
            'features_inquiry' => ['bedroom', 'bathroom', 'features'],
            default => [],
        };
    }

    /**
     * Check if message requires agent escalation.
     */
    public function requiresEscalation(string $intent, float $confidence): bool
    {
        // Low confidence responses should be escalated
        if ($confidence < 0.5) {
            return true;
        }
        
        // Specific intents that should be escalated
        if (in_array($intent, ['contact_request'])) {
            return false; // Already suggesting escalation in response
        }
        
        return false;
    }
}
