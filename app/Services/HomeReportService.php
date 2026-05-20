<?php

namespace App\Services;

use App\Models\HomeReport;
use App\Models\Property;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class HomeReportService
{
    /**
     * Create a home report record for a property.
     *
     * @param  Property  $property
     * @param  array  $data
     * @return HomeReport
     */
    public function createHomeReport(Property $property, array $data): HomeReport
    {
        $this->validateEnergyBand($data['energy_band'] ?? null);
        $this->validateCondition($data['property_condition'] ?? null);

        return HomeReport::create(array_merge($data, [
            'property_id' => $property->id,
            'report_type' => $data['report_type'] ?? 'scottish_home_report',
        ]));
    }

    /**
     * Upload a home report PDF and associate it with a report record.
     *
     * @param  HomeReport  $report
     * @param  UploadedFile  $file
     * @return HomeReport
     */
    public function uploadReportFile(HomeReport $report, UploadedFile $file): HomeReport
    {
        if ($file->getClientMimeType() !== 'application/pdf') {
            throw new InvalidArgumentException('Home report must be a PDF file.');
        }

        if ($report->file_path) {
            Storage::delete($report->file_path);
        }

        $path = $file->store("home_reports/{$report->property_id}", 'public');
        $url = Storage::url($path);

        $report->update([
            'file_path' => $path,
            'file_url' => $url,
        ]);

        return $report->fresh();
    }

    /**
     * Update condition ratings for a home report.
     *
     * @param  HomeReport  $report
     * @param  array  $conditionData  Associative array of section => condition rating (1, 2, 3)
     * @return HomeReport
     */
    public function updateConditionRatings(HomeReport $report, array $conditionData): HomeReport
    {
        foreach ($conditionData as $section => $rating) {
            if (!in_array($section, HomeReport::CONDITION_SECTIONS)) {
                throw new InvalidArgumentException("Invalid condition section: {$section}");
            }
            if (!in_array((string) $rating, ['1', '2', '3'])) {
                throw new InvalidArgumentException("Invalid condition rating '{$rating}'. Must be 1, 2, or 3.");
            }
        }

        $existing = $report->condition_categories ?? [];
        $merged = array_merge($existing, $conditionData);

        $report->update(['condition_categories' => $merged]);

        return $report->fresh();
    }

    /**
     * Get the overall condition (worst rating across all sections).
     *
     * @param  HomeReport  $report
     * @return string|null
     */
    public function getOverallCondition(HomeReport $report): ?string
    {
        $categories = $report->condition_categories;

        if (empty($categories)) {
            return $report->property_condition;
        }

        $maxCondition = max(array_values($categories));

        return (string) $maxCondition;
    }

    /**
     * Check if a property has a valid home report.
     *
     * @param  Property  $property
     * @return bool
     */
    public function hasValidReport(Property $property): bool
    {
        return HomeReport::where('property_id', $property->id)
            ->valid()
            ->exists();
    }

    /**
     * Get the latest home report for a property.
     *
     * @param  Property  $property
     * @return HomeReport|null
     */
    public function getLatestReport(Property $property): ?HomeReport
    {
        return HomeReport::where('property_id', $property->id)
            ->orderBy('survey_date', 'desc')
            ->first();
    }

    private function validateEnergyBand(?string $band): void
    {
        if ($band !== null && !in_array($band, ['A', 'B', 'C', 'D', 'E', 'F', 'G'])) {
            throw new InvalidArgumentException("Invalid energy band: {$band}. Must be A-G.");
        }
    }

    private function validateCondition(?string $condition): void
    {
        if ($condition !== null && !in_array($condition, ['1', '2', '3'])) {
            throw new InvalidArgumentException("Invalid property condition: {$condition}. Must be 1, 2, or 3.");
        }
    }
}
