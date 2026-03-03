<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Mail;

class SendToFriendService
{
    /**
     * Send a property listing to a friend via email.
     *
     * @param  Property  $property
     * @param  string  $recipientEmail
     * @param  string  $recipientName
     * @param  string  $senderName
     * @param  string  $senderEmail
     * @param  string|null  $personalMessage
     * @return bool
     */
    public function sendPropertyToFriend(
        Property $property,
        string $recipientEmail,
        string $recipientName,
        string $senderName,
        string $senderEmail,
        ?string $personalMessage = null
    ): bool {
        $this->validateEmail($recipientEmail);
        $this->validateEmail($senderEmail);

        $data = $this->buildEmailData($property, $recipientName, $senderName, $senderEmail, $personalMessage);

        Mail::send([], $data, function ($message) use ($recipientEmail, $recipientName, $senderName, $senderEmail, $data) {
            $message->to($recipientEmail, $recipientName)
                ->replyTo($senderEmail, $senderName)
                ->subject($data['subject'])
                ->html($data['body']);
        });

        return true;
    }

    /**
     * Build the email data for the send-to-friend email.
     */
    public function buildEmailData(
        Property $property,
        string $recipientName,
        string $senderName,
        string $senderEmail,
        ?string $personalMessage
    ): array {
        $subject = "{$senderName} thought you might be interested in this property";

        $propertyUrl = url('/properties/' . $property->id);

        $messageLines = [];
        if ($personalMessage) {
            $messageLines[] = htmlspecialchars($personalMessage, ENT_QUOTES, 'UTF-8');
            $messageLines[] = '';
        }

        $body = $this->renderEmailBody($property, $recipientName, $senderName, $senderEmail, $personalMessage, $propertyUrl);

        return [
            'subject' => $subject,
            'body' => $body,
            'property' => $property,
            'recipient_name' => $recipientName,
            'sender_name' => $senderName,
            'sender_email' => $senderEmail,
            'personal_message' => $personalMessage,
            'property_url' => $propertyUrl,
        ];
    }

    private function renderEmailBody(
        Property $property,
        string $recipientName,
        string $senderName,
        string $senderEmail,
        ?string $personalMessage,
        string $propertyUrl
    ): string {
        $escapedSender = htmlspecialchars($senderName, ENT_QUOTES, 'UTF-8');
        $escapedRecipient = htmlspecialchars($recipientName, ENT_QUOTES, 'UTF-8');
        $escapedTitle = htmlspecialchars($property->title, ENT_QUOTES, 'UTF-8');
        $escapedLocation = htmlspecialchars($property->location ?? '', ENT_QUOTES, 'UTF-8');
        $escapedMessage = $personalMessage ? htmlspecialchars($personalMessage, ENT_QUOTES, 'UTF-8') : '';
        $formattedPrice = '£' . number_format($property->price, 0);

        $personalMessageHtml = $escapedMessage
            ? "<p><em>\"{$escapedMessage}\"</em></p>"
            : '';

        return <<<HTML
        <html>
        <body>
            <p>Dear {$escapedRecipient},</p>
            <p>{$escapedSender} has seen a property they think you might be interested in.</p>
            {$personalMessageHtml}
            <h2>{$escapedTitle}</h2>
            <p><strong>Location:</strong> {$escapedLocation}</p>
            <p><strong>Price:</strong> {$formattedPrice}</p>
            <p><strong>Bedrooms:</strong> {$property->bedrooms}</p>
            <p><strong>Bathrooms:</strong> {$property->bathrooms}</p>
            <p><a href="{$propertyUrl}">View full property details</a></p>
            <hr>
            <p><small>This email was sent by {$escapedSender} ({$senderEmail}) using our property sharing feature.</small></p>
        </body>
        </html>
        HTML;
    }

    private function validateEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email address: {$email}");
        }
    }
}
