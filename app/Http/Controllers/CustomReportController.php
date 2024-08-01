<?php

namespace App\Http\Controllers;

use App\Http\Livewire\CustomReportForm;
use App\Services\DocumentGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CustomReportController extends Controller
{
    protected $documentGenerationService;

    public function __construct(DocumentGenerationService $documentGenerationService)
    {
        $this->documentGenerationService = $documentGenerationService;
    }

    public function index()
    {
        return view('custom-reports.index');
    }

    public function generateReport(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'report_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'properties' => 'nullable|array',
            'tenants' => 'nullable|array',
        ]);

        // Generate the report data
        $reportData = $this->generateReportData($validatedData);

        // Return the report data as JSON
        return response()->json($reportData);
    }

    public function exportReportToPdf(Request $request)
    {
        $reportData = $this->generateReportData($request->all());
        $pdfContent = $this->documentGenerationService->generatePdf('custom_report', $reportData);

        return Response::make($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="custom_report.pdf"',
        ]);
    }

    public function exportReportToExcel(Request $request)
    {
        $reportData = $this->generateReportData($request->all());
        $excelContent = $this->documentGenerationService->generateExcel('custom_report', $reportData);

        return Response::make($excelContent, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="custom_report.xlsx"',
        ]);
    }

    private function generateReportData($criteria)
    {
        // Implement the logic to generate report data based on the criteria
        // This is a placeholder and should be replaced with actual data generation logic
        return [
            'report_type' => $criteria['report_type'],
            'start_date' => $criteria['start_date'],
            'end_date' => $criteria['end_date'],
            'data' => [
                // Add your report data here
            ],
        ];
    }
}