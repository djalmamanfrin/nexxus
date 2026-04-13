<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reconciliation\ReconciliationRequest;
use App\Http\Resources\ExpenseResource;
use App\Http\Resources\PaymentResource;
use App\Models\Expense;
use App\Models\ExpenseStatus;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\Reconciliation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReconciliationController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::query()
            ->where('payment_status_id', PaymentStatus::UNRECONCILED)
            ->with('attachments', 'status', 'bankAccount')
            ->filter($request->all())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $expenses = Expense::query()
            ->where('expense_status_id', ExpenseStatus::UNRECONCILED)
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

    /**
     * @throws \Throwable
     */
    public function store(ReconciliationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            foreach ($data['payments'] as $payment) {
                Reconciliation::updateOrCreate(
                    [
                        'expense_id' => $data['expense_id'],
                        'payment_id' => $payment['id'],
                    ],
                    [
                        'amount' => $payment['amount'],
                        'linked_at' => now(),
                    ]
                );
            }
        });

        return back()->with(['success' => 'Conciliação realziada com sucesso']);
    }
}
