<?php

namespace App\Services;

use App\Models\Agent;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AgentPerformanceReport
{
    protected $documentGenerationService;

    public function __construct(DocumentGenerationService $documentGenerationService)
    {
        $this->documentGenerationService = $documentGenerationService;
    }

    public function generatePdfReport(Agent $agent, $startDate, $endDate)
    {
        $performanceData = $this->getPerformanceData($agent, $startDate, $endDate);
        $html = View::make('reports.agent-performance-pdf', $performanceData)->render();
        return $this->documentGenerationService->generatePdf($html);
    }

    public function generateExcelReport(Agent $agent, $startDate, $endDate)
    {
        $performanceData = $this->getPerformanceData($agent, $startDate, $endDate);
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Agent Performance Report');
        $sheet->setCellValue('A2', 'Agent Name: ' . $agent->name);
        $sheet->setCellValue('A3', 'Period: ' . $startDate . ' to ' . $endDate);

        $sheet->setCellValue('A5', 'Date');
        $sheet->setCellValue('B5', 'Sales Volume');
        $sheet->setCellValue('C5', 'Transactions');
        $sheet->setCellValue('D5', 'Customer Satisfaction');
        $sheet->setCellValue('E5', 'Avg. Days on Market');
        $sheet->setCellValue('F5', 'Lead Conversion Rate');

        $row = 6;
        foreach ($performanceData['metrics'] as $metric) {
            $sheet->setCellValue('A' . $row, $metric->date);
            $sheet->setCellValue('B' . $row, $metric->sales_volume);
            $sheet->setCellValue('C' . $row, $metric->number_of_transactions);
            $sheet->setCellValue('D' . $row, $metric->customer_satisfaction_rating);
            $sheet->setCellValue('E' . $row, $metric->average_days_on_market);
            $sheet->setCellValue('F' . $row, $metric->lead_conversion_rate);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'agent_performance_report');
        $writer->save($tempFile);

        return file_get_contents($tempFile);
    }

    private function getPerformanceData(Agent $agent, $startDate, $endDate)
    {
        $metrics = $agent->performanceMetrics()
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();

        return [
            'agent' => $agent,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'metrics' => $metrics,
            'totalSales' => $metrics->sum('sales_volume'),
            'averageRating' => $metrics->avg('customer_satisfaction_rating'),
            'totalTransactions' => $metrics->sum('number_of_transactions'),
        ];
    }
}