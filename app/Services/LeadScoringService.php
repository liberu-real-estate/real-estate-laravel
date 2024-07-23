<?php

namespace App\Services;

use App\Models\Lead;

class LeadScoringService
{
    public function calculateScore(Lead $lead)
    {
        $score = 0;

        // Basic information
        if ($lead->name) $score += 10;
        if ($lead->email) $score += 10;
        if ($lead->phone) $score += 10;

        // Interest
        switch ($lead->interest) {
            case 'buying':
                $score += 30;
                break;
            case 'selling':
                $score += 25;
                break;
            case 'renting':
                $score += 20;
                break;
            default:
                $score += 15;
        }

        // Activity
        $score += $lead->activities->count() * 5;

        // Cap the score at 100
        return min($score, 100);
    }

    public function updateLeadScore(Lead $lead)
    {
        $lead->score = $this->calculateScore($lead);
        $lead->save();
    }
}