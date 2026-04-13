<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpenseResource;
use App\Http\Resources\PaymentResource;
use App\Models\Expense;
use App\Models\ExpenseStatus;
use App\Models\Payment;
use App\Models\PaymentStatus;
use Illuminate\Http\Request;
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
            ->where('expense_status_id', ExpenseStatus::DONE)
            ->with(['attachments', 'costCenter', 'payee', 'status', 'category'])
            ->filter($request->all())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('reconciliation/Index', [
            'payments' => PaymentResource::collection($payments),
            'expenses' => ExpenseResource::collection($expenses),
            'search_by' => $request->search_by,
        ]);
    }
}
