<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Financial Overview (Cards)
        $thisMonthRevenue = Invoice::where('status', 'paid')
            ->whereYear('paid_at', now()->year)
            ->whereMonth('paid_at', now()->month)
            ->sum('amount');

        $thisYearRevenue = Invoice::where('status', 'paid')
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        // Expenses (Tower Costs)
        $currentMonthStr = now()->format('Y-m');
        $thisMonthExpenses = \App\Models\TowerMonthlyCost::where('month', $currentMonthStr)
            ->sum('total_cost');

        $thisYearExpenses = \App\Models\Tower::join('tower_monthly_costs', 'towers.id', '=', 'tower_monthly_costs.tower_id')
             ->where('tower_monthly_costs.month', 'like', now()->year . '-%')
             ->sum('tower_monthly_costs.total_cost'); // Simplified query

        $thisYearProfit = $thisYearRevenue - $thisYearExpenses;

        // Debts (Unpaid Invoices)
        $totalDebts = Invoice::where('status', 'unpaid')->sum('amount');

        // 2. Charts Data
        
        // A. Monthly Revenue vs Expenses (Current Year)
        $monthlyRevenue = Invoice::select(
                DB::raw('MONTH(paid_at) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'paid')
            ->whereYear('paid_at', now()->year)
            ->groupBy(DB::raw('MONTH(paid_at)'))
            ->pluck('total', 'month')
            ->toArray();

        // Expenses (Simplified for demo, just random values if table empty or fetch real)
        $monthlyExpenses = \App\Models\TowerMonthlyCost::select(
                DB::raw("CAST(SUBSTRING_INDEX(month, '-', -1) AS UNSIGNED) as month_num"),
                DB::raw('SUM(total_cost) as total')
            )
            ->where('month', 'like', now()->year . '-%')
            ->groupBy(DB::raw("CAST(SUBSTRING_INDEX(month, '-', -1) AS UNSIGNED)"))
            ->pluck('total', 'month_num')
            ->toArray();

        // Fill 0s for missing months
        $revenueData = [];
        $expensesData = [];
        $profitData = [];
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date("F", mktime(0, 0, 0, $i, 1)); // Month name
            $rev = $monthlyRevenue[$i] ?? 0;
            $exp = $monthlyExpenses[$i] ?? 0;
            
            $revenueData[] = $rev;
            $expensesData[] = $exp;
            $profitData[] = $rev - $exp;
        }

        // B. Revenue by Service Source (Hotspot vs Broadband)
        // Join Invoices -> Clients -> Type
        $revenueByType = Invoice::where('invoices.status', 'paid')
            ->join('clients', 'invoices.client_id', '=', 'clients.id')
            ->select('clients.type', DB::raw('sum(invoices.amount) as total'))
            ->groupBy('clients.type')
            ->pluck('total', 'clients.type')
            ->toArray();
        
        $revenuePieData = [
            'hotspot' => $revenueByType['hotspot'] ?? 0,
            'broadband' => $revenueByType['broadband'] ?? $revenueByType['pppoe'] ?? 0,
        ];

        // C. Expense Breakdown (This Year)
        $yearlyCosts = \App\Models\TowerMonthlyCost::where('month', 'like', now()->year . '-%')->get();
        $expenseBreakdown = [
            'electricity' => $yearlyCosts->sum('ampere_bill'), // Placeholder columns
            'fuel' => $yearlyCosts->sum('diesel_cost'),
            'maintenance' => $yearlyCosts->sum('maintenance_cost'),
            'rent' => $yearlyCosts->sum('monthly_rent'), 
        ];
        
        // D. Top Performing Towers (Revenue)
        $topTowers = Invoice::where('invoices.status', 'paid')
            ->join('clients', 'invoices.client_id', '=', 'clients.id')
            ->join('towers', 'clients.tower_id', '=', 'towers.id')
            ->select('towers.name', DB::raw('sum(invoices.amount) as total'))
            ->groupBy('towers.id', 'towers.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('accounting.reports.index', compact(
            'thisMonthRevenue', 'thisYearRevenue', 
            'thisMonthExpenses', 'thisYearExpenses', 'thisYearProfit',
            'totalDebts',
            'revenueData', 'expensesData', 'profitData', 'months',
            'revenuePieData', 'expenseBreakdown',
            'topTowers'
        ));
    }
}
