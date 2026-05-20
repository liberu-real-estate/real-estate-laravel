<?php

namespace App\Services;

use App\Models\Property;
use App\Models\ComplianceItem;
use App\Models\ComplianceDocument;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ComplianceService
{
    public function createComplianceItem(Property $property, array $data): ComplianceItem
    {
        return $property->complianceItems()->create(array_merge($data, [
            'team_id' => $property->team_id
        ]));
    }

    public function getOverdueItems(int $teamId = null): Collection
    {
        $query = ComplianceItem::overdue();

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        return $query->with(['property', 'assignedTo'])->get();
    }

    public function getItemsDueSoon(int $days = 30, int $teamId = null): Collection
    {
        $query = ComplianceItem::dueSoon();

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        return $query->with(['property', 'assignedTo'])->get();
    }

    public function getComplianceReport(int $teamId = null): array
    {
        $query = ComplianceItem::query();

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        $total = $query->count();
        $completed = $query->where('status', 'completed')->count();
        $overdue = $query->where('required_by_date', '<', now())
            ->where('status', '!=', 'completed')->count();
        $dueSoon = $query->where('required_by_date', '<=', now()->addDays(30))
            ->where('status', '!=', 'completed')->count();

        $byType = $query->groupBy('compliance_type')
            ->selectRaw('compliance_type, count(*) as count, 
                        sum(case when status = "completed" then 1 else 0 end) as completed_count')
            ->get()
            ->keyBy('compliance_type');

        $byRisk = $query->groupBy('risk_level')
            ->selectRaw('risk_level, count(*) as count')
            ->get()
            ->keyBy('risk_level');

        return [
            'summary' => [
                'total' => $total,
                'completed' => $completed,
                'overdue' => $overdue,
                'due_soon' => $dueSoon,
                'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0
            ],
            'by_type' => $byType,
            'by_risk_level' => $byRisk,
            'trends' => $this->getComplianceTrends($teamId)
        ];
    }

    public function generatePropertyComplianceChecklist(Property $property): array
    {
        $checklist = [];

        // Standard UK property compliance items
        $standardItems = [
            [
                'compliance_type' => 'epc',
                'title' => 'Energy Performance Certificate',
                'description' => 'Valid EPC required for marketing property',
                'required_by_date' => now()->addDays(30),
                'priority_level' => 3,
                'risk_level' => 3
            ],
            [
                'compliance_type' => 'gas_safety',
                'title' => 'Gas Safety Certificate',
                'description' => 'Annual gas safety check required for rental properties',
                'required_by_date' => now()->addDays(60),
                'priority_level' => 4,
                'risk_level' => 4
            ],
            [
                'compliance_type' => 'electrical',
                'title' => 'Electrical Installation Condition Report',
                'description' => 'EICR required every 5 years for rental properties',
                'required_by_date' => now()->addDays(90),
                'priority_level' => 3,
                'risk_level' => 3
            ],
            [
                'compliance_type' => 'fire_safety',
                'title' => 'Fire Safety Assessment',
                'description' => 'Fire safety measures and equipment check',
                'required_by_date' => now()->addDays(45),
                'priority_level' => 4,
                'risk_level' => 4
            ]
        ];

        // Add property-specific items based on type
        if ($property->property_type === 'flat' || $property->property_type === 'apartment') {
            $standardItems[] = [
                'compliance_type' => 'building_insurance',
                'title' => 'Building Insurance Certificate',
                'description' => 'Valid building insurance for leasehold property',
                'required_by_date' => now()->addDays(30),
                'priority_level' => 3,
                'risk_level' => 2
            ];
        }

        if ($property->bedrooms >= 3) {
            $standardItems[] = [
                'compliance_type' => 'legionella',
                'title' => 'Legionella Risk Assessment',
                'description' => 'Legionella risk assessment for larger properties',
                'required_by_date' => now()->addDays(60),
                'priority_level' => 2,
                'risk_level' => 3
            ];
        }

        foreach ($standardItems as $item) {
            // Check if item already exists
            $existing = $property->complianceItems()
                ->where('compliance_type', $item['compliance_type'])
                ->where('status', '!=', 'completed')
                ->first();

            if (!$existing) {
                $checklist[] = $item;
            }
        }

        return $checklist;
    }

    public function createStandardComplianceItems(Property $property): Collection
    {
        $checklist = $this->generatePropertyComplianceChecklist($property);
        $created = collect();

        foreach ($checklist as $item) {
            $complianceItem = $this->createComplianceItem($property, $item);
            $created->push($complianceItem);
        }

        return $created;
    }

    public function updateItemStatus(ComplianceItem $item, string $status, array $additionalData = []): ComplianceItem
    {
        $updateData = array_merge(['status' => $status], $additionalData);

        if ($status === 'completed') {
            $updateData['completed_date'] = now();
        }

        $item->update($updateData);

        return $item->fresh();
    }

    public function addDocument(ComplianceItem $item, array $documentData): ComplianceDocument
    {
        return $item->complianceDocuments()->create($documentData);
    }

    public function getExpiringCertificates(int $days = 30, int $teamId = null): Collection
    {
        $query = ComplianceItem::whereNotNull('certificate_expiry')
            ->where('certificate_expiry', '<=', now()->addDays($days))
            ->where('certificate_expiry', '>=', now());

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        return $query->with(['property'])->get();
    }

    public function scheduleRenewal(ComplianceItem $item): ComplianceItem
    {
        if ($item->certificate_expiry && $item->renewal_required) {
            // Create new compliance item for renewal
            $renewalData = $item->toArray();
            unset($renewalData['id'], $renewalData['created_at'], $renewalData['updated_at']);

            $renewalData['title'] = 'Renewal: ' . $renewalData['title'];
            $renewalData['required_by_date'] = $item->certificate_expiry->subDays(30);
            $renewalData['status'] = 'pending';
            $renewalData['completed_date'] = null;
            $renewalData['certificate_number'] = null;
            $renewalData['certificate_expiry'] = null;

            return ComplianceItem::create($renewalData);
        }

        return $item;
    }

    public function getRiskAssessment(Property $property): array
    {
        $items = $property->complianceItems;
        $riskScore = 0;
        $riskFactors = [];

        foreach ($items as $item) {
            if ($item->isOverdue()) {
                $riskScore += $item->risk_level * 10;
                $riskFactors[] = "Overdue: {$item->title}";
            } elseif ($item->isDueSoon()) {
                $riskScore += $item->risk_level * 5;
                $riskFactors[] = "Due soon: {$item->title}";
            }

            if ($item->certificate_expiry && $item->certificate_expiry < now()) {
                $riskScore += 20;
                $riskFactors[] = "Expired certificate: {$item->title}";
            }
        }

        $riskLevel = $this->calculateRiskLevel($riskScore);

        return [
            'risk_score' => min($riskScore, 100),
            'risk_level' => $riskLevel,
            'risk_factors' => $riskFactors,
            'recommendations' => $this->getRiskRecommendations($riskLevel, $items)
        ];
    }

    private function getComplianceTrends(int $teamId = null): array
    {
        $query = ComplianceItem::query();

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        $last6Months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthKey = $month->format('Y-m');

            $completed = $query->clone()
                ->whereYear('completed_date', $month->year)
                ->whereMonth('completed_date', $month->month)
                ->count();

            $created = $query->clone()
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $last6Months[] = [
                'month' => $month->format('M Y'),
                'completed' => $completed,
                'created' => $created
            ];
        }

        return $last6Months;
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

    private function getRiskRecommendations(string $riskLevel, Collection $items): array
    {
        $recommendations = [];

        if ($riskLevel === 'Critical') {
            $recommendations[] = 'Immediate action required - property may not be legally compliant';
            $recommendations[] = 'Consider removing property from market until compliance achieved';
        }

        if (in_array($riskLevel, ['Critical', 'High'])) {
            $recommendations[] = 'Schedule urgent compliance review meeting';
            $recommendations[] = 'Prioritize overdue items immediately';
        }

        $overdueItems = $items->filter(fn($item) => $item->isOverdue());
        if ($overdueItems->count() > 0) {
            $recommendations[] = "Address {$overdueItems->count()} overdue compliance items";
        }

        $expiringSoon = $items->filter(fn($item) => 
            $item->certificate_expiry && $item->certificate_expiry <= now()->addDays(30)
        );
        if ($expiringSoon->count() > 0) {
            $recommendations[] = "Renew {$expiringSoon->count()} expiring certificates";
        }

        return $recommendations;
    }
}