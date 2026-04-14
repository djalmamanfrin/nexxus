<?php

namespace App\Http\Controllers;

use App\Actions\Reconciliation\StoreReconciliationAction;
use App\Http\Requests\Reconciliation\ReconciliationRequest;
use App\Http\Resources\ExpenseResource;
use App\Http\Resources\PaymentResource;
use App\Models\Expense;
use App\Models\ExpenseStatus;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\Reconciliation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReconciliationController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::query()
            ->whereIn('payment_status_id', [PaymentStatus::UNRECONCILED, PaymentStatus::PARTIAL, PaymentStatus::EXCESS])
            ->with('attachments', 'status', 'bankAccount')
            ->filter($request->all())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $expenses = Expense::query()
            ->whereIn('expense_status_id', [ExpenseStatus::UNRECONCILED, ExpenseStatus::PARTIAL, ExpenseStatus::EXCESS])
            ->with(['attachments', 'costCenter', 'payee', 'status', 'category'])
            ->filter($request->all())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('reconciliations/Index', [
            'payments' => PaymentResource::collection($payments),
            'expenses' => ExpenseResource::collection($expenses),
            'search_by' => $request->search_by,
        ]);
    }

    public function expensePartials(Expense $expense): JsonResponse
    {
        $partials = $expense->reconciliations()->get();
        return response()->json($partials);
    }

    /**
     * @throws \Throwable
     */
    public function store(ReconciliationRequest $request, StoreReconciliationAction $action): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $action) {
            $action->execute(
                $data['expenses'],
                $data['payments']
            );
        });

        return back()->with(['success' => 'Conciliação realziada com sucesso']);
    }
}
