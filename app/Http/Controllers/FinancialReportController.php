<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\RentalCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialReportController extends Controller
{
    public function index()
    {
        return view('financial-reports.index');
    }

    public function generateReport(Request $request)
    {
        $validatedData = $request->validate([
            'report_type' => 'required|in:income,expenses,profit_loss',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $startDate = $validatedData['start_date'];
        $endDate = $validatedData['end_date'];

        switch ($validatedData['report_type']) {
            case 'income':
                $data = $this->getIncomeReport($startDate, $endDate);
                break;
            case 'expenses':
                $data = $this->getExpensesReport($startDate, $endDate);
                break;
            case 'profit_loss':
                $data = $this->getProfitLossReport($startDate, $endDate);
                break;
        }

        return view('financial-reports.report', compact('data', 'startDate', 'endDate'));
    }

    private function getIncomeReport($startDate, $endDate)
    {
        return Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->select(DB::raw('DATE(payment_date) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getExpensesReport($startDate, $endDate)
    {
        // Implement expense tracking logic here
        // This is a placeholder and should be replaced with actual expense data
        return collect([]);
    }

    private function getProfitLossReport($startDate, $endDate)
    {
        $income = $this->getIncomeReport($startDate, $endDate);
        $expenses = $this->getExpensesReport($startDate, $endDate);

        return [
            'income' => $income,
            'expenses' => $expenses,
            'profit' => $income->sum('total') - $expenses->sum('total'),
        ];
    }
}