<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Property;
use App\Models\ViewingFeedback;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;

class ViewingFeedbackService
{
    /**
     * Request feedback from a viewer after their viewing appointment.
     *
     * @param  Appointment  $appointment
     * @param  string  $viewerEmail
     * @param  string  $viewerName
     * @return ViewingFeedback
     */
    public function requestFeedback(Appointment $appointment, string $viewerEmail, string $viewerName): ViewingFeedback
    {
        if (!filter_var($viewerEmail, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid viewer email address: {$viewerEmail}");
        }

        $feedback = ViewingFeedback::create([
            'appointment_id' => $appointment->getKey(),
            'property_id' => $appointment->property_id,
            'viewer_id' => $appointment->user_id,
            'viewer_name' => $viewerName,
            'viewer_email' => $viewerEmail,
            'feedback_requested_at' => now(),
            'team_id' => $appointment->team_id,
        ]);

        $this->sendFeedbackRequestEmail($feedback, $viewerEmail, $viewerName);

        return $feedback;
    }

    /**
     * Submit feedback for a viewing.
     *
     * @param  ViewingFeedback  $feedback
     * @param  array  $data
     * @return ViewingFeedback
     */
    public function submitFeedback(ViewingFeedback $feedback, array $data): ViewingFeedback
    {
        if ($feedback->hasBeenSubmitted()) {
            throw new \RuntimeException('Feedback has already been submitted for this viewing.');
        }

        $allowedFields = [
            'overall_rating', 'price_rating', 'condition_rating',
            'location_rating', 'size_rating', 'positive_comments',
            'negative_comments', 'general_comments', 'interest_level',
            'would_make_offer', 'offer_price',
        ];

        $filteredData = array_intersect_key($data, array_flip($allowedFields));
        $filteredData['feedback_submitted_at'] = now();

        $feedback->update($filteredData);

        return $feedback->fresh();
    }

    /**
     * Find a feedback record by its token (for unauthenticated links).
     *
     * @param  string  $token
     * @return ViewingFeedback|null
     */
    public function findByToken(string $token): ?ViewingFeedback
    {
        return ViewingFeedback::where('token', $token)->first();
    }

    /**
     * Get a summary of feedback for a property.
     *
     * @param  Property  $property
     * @return array
     */
    public function getPropertyFeedbackSummary(Property $property): array
    {
        $feedbacks = ViewingFeedback::where('property_id', $property->id)
            ->submitted()
            ->get();

        if ($feedbacks->isEmpty()) {
            return [
                'total_viewings' => 0,
                'average_overall_rating' => null,
                'interest_breakdown' => [],
                'would_make_offer_count' => 0,
            ];
        }

        $avgRatings = [
            'overall' => $feedbacks->avg('overall_rating'),
            'price' => $feedbacks->avg('price_rating'),
            'condition' => $feedbacks->avg('condition_rating'),
            'location' => $feedbacks->avg('location_rating'),
            'size' => $feedbacks->avg('size_rating'),
        ];

        $interestBreakdown = $feedbacks->groupBy('interest_level')
            ->map(fn ($group) => $group->count())
            ->toArray();

        return [
            'total_viewings' => $feedbacks->count(),
            'average_overall_rating' => $avgRatings['overall'] ? round($avgRatings['overall'], 1) : null,
            'average_ratings' => array_map(fn ($v) => $v ? round($v, 1) : null, $avgRatings),
            'interest_breakdown' => $interestBreakdown,
            'would_make_offer_count' => $feedbacks->where('would_make_offer', true)->count(),
            'interested_viewers' => $feedbacks->whereIn('interest_level', ['very_interested', 'interested'])->count(),
        ];
    }

    private function sendFeedbackRequestEmail(ViewingFeedback $feedback, string $viewerEmail, string $viewerName): void
    {
        $feedbackUrl = url('/feedback/' . $feedback->token);
        $escapedName = htmlspecialchars($viewerName, ENT_QUOTES, 'UTF-8');
        $escapedTitle = htmlspecialchars($feedback->property->title ?? 'the property', ENT_QUOTES, 'UTF-8');

        $body = <<<HTML
        <html><body>
        <p>Dear {$escapedName},</p>
        <p>Thank you for viewing <strong>{$escapedTitle}</strong>. We would love to hear your thoughts.</p>
        <p><a href="{$feedbackUrl}">Click here to leave your feedback</a></p>
        <p>Your feedback helps us improve our service.</p>
        </body></html>
        HTML;

        Mail::send([], [], function ($message) use ($viewerEmail, $viewerName, $body) {
            $message->to($viewerEmail, $viewerName)
                ->subject('How was your viewing? Share your feedback')
                ->html($body);
        });
    }
}
