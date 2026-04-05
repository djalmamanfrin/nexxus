<?php

namespace App\Http\Controllers;

use App\Models\CostCenter;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $workId = $request->get('work_id');
        $start = $request->get('start_date');
        $end   = $request->get('end_date');

        $cacheKey = 'dashboard:' . md5(json_encode($request->all()));

        $data = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($workId, $start, $end) {

            // ========================
            // EXPENSE BASE QUERY
            // ========================
            $expenseBase = Expense::query()
                ->join('cost_centers', 'expenses.cost_center_id', '=', 'cost_centers.id')
                ->join('works', 'cost_centers.work_id', '=', 'works.id');

            if ($workId) {
                $expenseBase->where('works.id', $workId);
            }

            if ($start && $end) {
                $expenseBase->whereBetween('expenses.competence_date', [$start, $end]);
            }

            // ========================
            // EXPENSES BY WORK
            // ========================
            $expensesByWork = Expense::query()
                ->join('cost_centers', 'expenses.cost_center_id', '=', 'cost_centers.id')
                ->join('works', 'cost_centers.work_id', '=', 'works.id')
                ->select('works.id', 'works.name', DB::raw('SUM(expenses.amount) as total'))
                ->groupBy('works.id', 'works.name')
                ->get();

            // ========================
            // ORÇADO VS REAL
            // ========================
            $budgetVsReal = CostCenter::query()
                ->join('works', 'cost_centers.work_id', '=', 'works.id')
                ->leftJoin('expenses', 'expenses.cost_center_id', '=', 'cost_centers.id')
                ->select(
                    'works.name',
                    DB::raw('SUM(cost_centers.budget) as budget'),
                    DB::raw('SUM(expenses.amount) as expenses')
                )
                ->groupBy('works.name')
                ->get();

            // ========================
            // EXPENSES BY MONTH
            // ========================
            $expensesByMonth = (clone $expenseBase)
                ->select(
                    DB::raw("DATE_FORMAT(expenses.competence_date, '%Y-%m') as month"),
                    DB::raw('SUM(expenses.amount) as total')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // ========================
            // COST CENTER
            // ========================
            $expensesByCostCenter = (clone $expenseBase)
                ->select(
                    'cost_centers.code as name',
                    DB::raw('SUM(expenses.amount) as total')
                )
                ->groupBy('cost_centers.code')
                ->get();

            // ========================
            // PAYEE (FIX)
            // ========================
            $expensesByPayee = (clone $expenseBase)
                ->leftJoin('payees', 'expenses.payee_id', '=', 'payees.id')
                ->select(
                    DB::raw('COALESCE(payees.name, "Sem payee") as name'),
                    DB::raw('SUM(expenses.amount) as total')
                )
                ->groupBy('name')
                ->get();

            // ========================
            // TOTALS
            // ========================
            $totalBudget = CostCenter::query()->sum('budget');
            $totalExpenses = (clone $expenseBase)->sum('expenses.amount');
            $totalPayments = (clone $expenseBase)
                ->join('payments', 'payments.expense_id', '=', 'expenses.id')
                ->sum('payments.amount');

            // ========================
            // BURN RATE
            // ========================
            $months = max(1, (clone $expenseBase)->distinct(DB::raw("DATE_FORMAT(expenses.competence_date, '%Y-%m')"))->count());
            $burnRate = $totalExpenses / $months;

            // ========================
            // FORECAST
            // ========================
            $remaining = $totalBudget - $totalExpenses;
            $monthsToEnd = $burnRate > 0 ? ($remaining / $burnRate) : 0;

            return [
                'filters' => [
                    'work_id' => $workId,
                    'start_date' => $start,
                    'end_date' => $end,
                ],
                'expensesByWork' => $expensesByWork,
                'budgetVsReal' => $budgetVsReal,
                'expensesByCostCenter' => $expensesByCostCenter,
                'expensesByMonth' => $expensesByMonth,
                'expensesByPayee' => $expensesByPayee,
                'totals' => [
                    'payments' => $totalPayments,
                    'expenses' => $totalExpenses,
                    'budget' => $totalBudget,
                    'burn_rate' => $burnRate,
                    'forecast_months' => $monthsToEnd,
                ],
            ];
        });

        return Inertia::render('dashboard/Index', $data);
    }
}
