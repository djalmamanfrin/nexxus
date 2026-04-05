<?php

namespace App\Http\Controllers;

use App\Models\CostCenter;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // ========================
        // EXPENSES BY WORK
        // ========================
        $expensesByWork = Expense::query()
            ->join('cost_centers', 'expenses.cost_center_id', '=', 'cost_centers.id')
            ->join('works', 'cost_centers.work_id', '=', 'works.id')
            ->select(
                'works.name',
                DB::raw('SUM(expenses.amount) as total')
            )
            ->groupBy('works.name')
            ->get();

        $expensesByCostCenter = Expense::query()
        ->join('cost_centers', 'expenses.cost_center_id', '=', 'cost_centers.id')
        ->select(
            'cost_centers.code as name',
            DB::raw('SUM(expenses.amount) as total')
        )
        ->groupBy('cost_centers.code')
        ->get();

        // ========================
        // BUDGET BY WORK
        // ========================
        $budgetByWork = CostCenter::query()
            ->join('works', 'cost_centers.work_id', '=', 'works.id')
            ->select(
                'works.name',
                DB::raw('SUM(cost_centers.budget) as total')
            )
            ->groupBy('works.name')
            ->get();

        // ========================
        // EXPENSES BY MONTH
        // ========================
        $expensesByMonth = Expense::query()
            ->select(
                DB::raw("DATE_FORMAT(competence_date, '%Y-%m') as month"),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ========================
        // TOTALS
        // ========================
        $totals = [
            'expenses' => Expense::query()->sum('amount'),
            'payments' => Payment::query()->sum('amount'),
            'budget'   => CostCenter::query()->sum('budget'),
        ];

        return Inertia::render('dashboard/Index', [
            'expensesByWork' => $expensesByWork,
            'budgetByWork' => $budgetByWork,
            'expensesByCostCenter' => $expensesByCostCenter,
            'expensesByMonth' => $expensesByMonth,
            'totals' => $totals,
        ]);
    }
}
