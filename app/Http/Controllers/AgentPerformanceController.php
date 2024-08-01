<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentPerformanceMetrics;
use App\Services\AgentPerformanceReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AgentPerformanceController extends Controller
{
    protected $reportService;

    public function __construct(AgentPerformanceReport $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $agents = Agent::with('performanceMetrics')->get();
        return view('agent-performance.index', compact('agents'));
    }

    public function show(Agent $agent)
    {
        $performanceMetrics = $agent->performanceMetrics()->latest()->take(12)->get();
        return view('agent-performance.show', compact('agent', 'performanceMetrics'));
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'format' => 'required|in:pdf,excel',
        ]);

        $agent = Agent::findOrFail($request->agent_id);
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($request->format === 'pdf') {
            $content = $this->reportService->generatePdfReport($agent, $startDate, $endDate);
            $filename = "agent_performance_report_{$agent->id}.pdf";
            $contentType = 'application/pdf';
        } else {
            $content = $this->reportService->generateExcelReport($agent, $startDate, $endDate);
            $filename = "agent_performance_report_{$agent->id}.xlsx";
            $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        }

        return Response::make($content, 200, [
            'Content-Type' => $contentType,
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}