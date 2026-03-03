<?php

namespace App\Services;

use App\Models\Property;
use App\Models\SalesProgression;
use App\Models\Transaction;
use InvalidArgumentException;

class SalesProgressionService
{
    /**
     * Default checklist items for a residential sale.
     */
    public const DEFAULT_CHECKLIST = [
        ['key' => 'offer_agreed', 'label' => 'Offer Agreed', 'completed' => false],
        ['key' => 'memorandum_sent', 'label' => 'Memorandum of Sale Sent', 'completed' => false],
        ['key' => 'solicitors_instructed', 'label' => 'Solicitors Instructed by Both Parties', 'completed' => false],
        ['key' => 'id_checks', 'label' => 'ID Checks Completed', 'completed' => false],
        ['key' => 'searches_ordered', 'label' => 'Searches Ordered', 'completed' => false],
        ['key' => 'mortgage_application', 'label' => 'Mortgage Application Submitted', 'completed' => false],
        ['key' => 'survey_booked', 'label' => 'Survey Booked', 'completed' => false],
        ['key' => 'survey_complete', 'label' => 'Survey Complete', 'completed' => false],
        ['key' => 'searches_received', 'label' => 'Searches Received', 'completed' => false],
        ['key' => 'enquiries_raised', 'label' => 'Enquiries Raised', 'completed' => false],
        ['key' => 'enquiries_answered', 'label' => 'Enquiries Answered', 'completed' => false],
        ['key' => 'mortgage_offer', 'label' => 'Mortgage Offer Received', 'completed' => false],
        ['key' => 'exchange_date_agreed', 'label' => 'Exchange Date Agreed', 'completed' => false],
        ['key' => 'contracts_signed', 'label' => 'Contracts Signed', 'completed' => false],
        ['key' => 'deposit_paid', 'label' => 'Deposit Paid', 'completed' => false],
        ['key' => 'exchanged', 'label' => 'Exchanged', 'completed' => false],
        ['key' => 'completion_date_set', 'label' => 'Completion Date Set', 'completed' => false],
        ['key' => 'completion_funds_sent', 'label' => 'Completion Funds Sent', 'completed' => false],
        ['key' => 'keys_released', 'label' => 'Keys Released', 'completed' => false],
        ['key' => 'completed', 'label' => 'Completed', 'completed' => false],
    ];

    /**
     * Create a new sales progression record for a property.
     *
     * @param  Property  $property
     * @param  array  $data
     * @return SalesProgression
     */
    public function createProgression(Property $property, array $data = []): SalesProgression
    {
        return SalesProgression::create(array_merge([
            'property_id' => $property->id,
            'stage' => 'offer_accepted',
            'checklist_items' => self::DEFAULT_CHECKLIST,
        ], $data));
    }

    /**
     * Advance the sales progression to the next stage.
     *
     * @param  SalesProgression  $progression
     * @return SalesProgression
     */
    public function advanceStage(SalesProgression $progression): SalesProgression
    {
        $stages = array_keys(SalesProgression::STAGES);
        $currentIndex = array_search($progression->stage, $stages);

        if ($currentIndex === false || $currentIndex >= count($stages) - 1) {
            throw new \RuntimeException('Cannot advance: already at the final stage.');
        }

        $progression->update(['stage' => $stages[$currentIndex + 1]]);

        return $progression->fresh();
    }

    /**
     * Update a specific stage manually.
     *
     * @param  SalesProgression  $progression
     * @param  string  $stage
     * @return SalesProgression
     */
    public function updateStage(SalesProgression $progression, string $stage): SalesProgression
    {
        if (!array_key_exists($stage, SalesProgression::STAGES)) {
            throw new InvalidArgumentException("Invalid stage: {$stage}");
        }

        $progression->update(['stage' => $stage]);

        return $progression->fresh();
    }

    /**
     * Update a checklist item's completion status.
     *
     * @param  SalesProgression  $progression
     * @param  string  $itemKey
     * @param  bool  $completed
     * @return SalesProgression
     */
    public function updateChecklistItem(SalesProgression $progression, string $itemKey, bool $completed): SalesProgression
    {
        $checklist = $progression->checklist_items ?? self::DEFAULT_CHECKLIST;

        $updated = false;
        foreach ($checklist as &$item) {
            if ($item['key'] === $itemKey) {
                $item['completed'] = $completed;
                $updated = true;
                break;
            }
        }
        unset($item);

        if (!$updated) {
            throw new InvalidArgumentException("Checklist item '{$itemKey}' not found.");
        }

        $progression->update(['checklist_items' => $checklist]);

        return $progression->fresh();
    }

    /**
     * Get the completion percentage of the checklist.
     *
     * @param  SalesProgression  $progression
     * @return int
     */
    public function getChecklistCompletionPercentage(SalesProgression $progression): int
    {
        $checklist = $progression->checklist_items;

        if (empty($checklist)) {
            return 0;
        }

        $completed = count(array_filter($checklist, fn ($item) => $item['completed']));

        return (int) round(($completed / count($checklist)) * 100);
    }

    /**
     * Get active sales progressions for a team.
     *
     * @param  int  $teamId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveProgressions(int $teamId)
    {
        return SalesProgression::where('team_id', $teamId)
            ->active()
            ->with(['property', 'agent'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
