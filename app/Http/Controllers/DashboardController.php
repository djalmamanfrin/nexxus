<?php

namespace App\Http\Controllers;

use App\Models\CostCenter;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $workIds = $request->get('work_ids');
        $start = $request->get('start_date');
        $end   = $request->get('end_date');

        $cacheKey = 'dashboard:' . md5(json_encode($request->all()));

        $data = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($workIds, $start, $end) {

            // ========================
            // EXPENSE BASE QUERY
            // ========================
            $expenseBase = Expense::query()
                ->join('cost_centers', 'expenses.cost_center_id', '=', 'cost_centers.id')
                ->join('cost_center_types', 'cost_centers.cost_center_type_id', '=', 'cost_center_types.id')
                ->join('works', 'cost_centers.work_id', '=', 'works.id');

            if ($workIds) {
                $expenseBase->whereIn('works.id', $workIds);
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
                    'cost_center_types.code as name',
                    DB::raw('SUM(expenses.amount) as total')
                )
                ->groupBy('cost_center_types.code')
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
                ->join('reconciliation', 'reconciliation.expense_id', '=', 'expenses.id')
                ->sum('reconciliation.amount');

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
                'works' => Work::select('id as value', 'name as label')->get(),
                'filters' => [
                    'work_ids' => $workIds,
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
