<?php

namespace App\Services;

use App\Models\PropertyChain;
use App\Models\ChainLink;
use App\Models\Property;
use App\Models\Buyer;
use Illuminate\Support\Collection;

class PropertyChainService
{
    public function createChain(array $data): PropertyChain
    {
        return PropertyChain::create($data);
    }

    public function addLinkToChain(PropertyChain $chain, array $linkData): ChainLink
    {
        $linkData['position_in_chain'] = $this->getNextPosition($chain);

        $link = $chain->chainLinks()->create($linkData);

        // Update chain length
        $chain->update([
            'total_chain_length' => $chain->chainLinks()->count()
        ]);

        return $link;
    }

    public function getChainProgress(PropertyChain $chain): array
    {
        $links = $chain->chainLinks;
        $totalLinks = $links->count();

        if ($totalLinks === 0) {
            return [
                'progress_percentage' => 0,
                'completed_links' => 0,
                'total_links' => 0,
                'bottlenecks' => [],
                'next_actions' => []
            ];
        }

        $completedLinks = $links->where('status', 'completed')->count();
        $progressPercentage = ($completedLinks / $totalLinks) * 100;

        $bottlenecks = $links->where('status', 'blocked')
            ->merge($links->where('status', 'delayed'))
            ->values();

        $nextActions = $this->getNextActions($chain);

        return [
            'progress_percentage' => round($progressPercentage, 2),
            'completed_links' => $completedLinks,
            'total_links' => $totalLinks,
            'bottlenecks' => $bottlenecks,
            'next_actions' => $nextActions,
            'estimated_completion' => $this->calculateEstimatedCompletion($chain)
        ];
    }

    public function identifyBottlenecks(PropertyChain $chain): Collection
    {
        return $chain->chainLinks()
            ->where(function ($query) {
                $query->where('status', 'blocked')
                    ->orWhere('status', 'delayed')
                    ->orWhere(function ($q) {
                        $q->where('mortgage_approved', false)
                          ->where('estimated_completion', '<', now()->addDays(30));
                    });
            })
            ->get();
    }

    public function updateLinkStatus(ChainLink $link, string $status, array $additionalData = []): ChainLink
    {
        $updateData = array_merge(['status' => $status], $additionalData);

        if ($status === 'completed') {
            $updateData['actual_completion'] = now();
        }

        $link->update($updateData);

        // Check if entire chain is complete
        $this->checkChainCompletion($link->propertyChain);

        return $link->fresh();
    }

    public function getChainRisk(PropertyChain $chain): array
    {
        $links = $chain->chainLinks;
        $riskFactors = [];
        $riskScore = 0;

        foreach ($links as $link) {
            // Check for mortgage issues
            if (!$link->mortgage_approved && $link->estimated_completion <= now()->addDays(30)) {
                $riskFactors[] = "Mortgage not approved for position {$link->position_in_chain}";
                $riskScore += 30;
            }

            // Check for survey issues
            if (!$link->survey_completed && $link->estimated_completion <= now()->addDays(14)) {
                $riskFactors[] = "Survey not completed for position {$link->position_in_chain}";
                $riskScore += 20;
            }

            // Check for legal work delays
            if ($link->legal_work_status !== 'completed' && $link->estimated_completion <= now()->addDays(21)) {
                $riskFactors[] = "Legal work pending for position {$link->position_in_chain}";
                $riskScore += 25;
            }

            // Check for blocking issues
            if (!empty($link->blocking_issues)) {
                $riskFactors[] = "Blocking issues at position {$link->position_in_chain}";
                $riskScore += 40;
            }
        }

        $riskLevel = $this->calculateRiskLevel($riskScore);

        return [
            'risk_score' => min($riskScore, 100),
            'risk_level' => $riskLevel,
            'risk_factors' => $riskFactors,
            'recommendations' => $this->getRiskRecommendations($riskLevel, $riskFactors)
        ];
    }

    private function getNextPosition(PropertyChain $chain): int
    {
        return $chain->chainLinks()->max('position_in_chain') + 1 ?? 1;
    }

    private function getNextActions(PropertyChain $chain): array
    {
        $actions = [];

        foreach ($chain->chainLinks as $link) {
            if ($link->status === 'pending' || $link->status === 'in_progress') {
                if (!$link->mortgage_approved) {
                    $actions[] = "Chase mortgage approval for position {$link->position_in_chain}";
                }
                if (!$link->survey_completed) {
                    $actions[] = "Schedule survey for position {$link->position_in_chain}";
                }
                if ($link->legal_work_status !== 'completed') {
                    $actions[] = "Follow up on legal work for position {$link->position_in_chain}";
                }
            }
        }

        return $actions;
    }

    private function calculateEstimatedCompletion(PropertyChain $chain): ?string
    {
        $latestCompletion = $chain->chainLinks()
            ->whereNotNull('estimated_completion')
            ->max('estimated_completion');

        return $latestCompletion;
    }

    private function checkChainCompletion(PropertyChain $chain): void
    {
        $totalLinks = $chain->chainLinks()->count();
        $completedLinks = $chain->chainLinks()->where('status', 'completed')->count();

        if ($totalLinks > 0 && $totalLinks === $completedLinks) {
            $chain->update([
                'status' => 'completed',
                'actual_completion_date' => now()
            ]);
        }
    }

    private function calculateRiskLevel(int $riskScore): string
    {
        return match(true) {
            $riskScore >= 80 => 'Critical',
            $riskScore >= 60 => 'High',
            $riskScore >= 40 => 'Medium',
            $riskScore >= 20 => 'Low',
            default => 'Minimal'
        };
    }

    private function getRiskRecommendations(string $riskLevel, array $riskFactors): array
    {
        $recommendations = [];

        if ($riskLevel === 'Critical' || $riskLevel === 'High') {
            $recommendations[] = 'Schedule urgent review meeting with all parties';
            $recommendations[] = 'Consider backup options for high-risk links';
        }

        if (in_array($riskLevel, ['High', 'Medium'])) {
            $recommendations[] = 'Increase communication frequency with all parties';
            $recommendations[] = 'Monitor progress daily';
        }

        foreach ($riskFactors as $factor) {
            if (str_contains($factor, 'Mortgage')) {
                $recommendations[] = 'Contact mortgage broker immediately';
            }
            if (str_contains($factor, 'Survey')) {
                $recommendations[] = 'Chase surveyor for completion date';
            }
            if (str_contains($factor, 'Legal')) {
                $recommendations[] = 'Contact solicitors for status update';
            }
        }

        return array_unique($recommendations);
    }
}