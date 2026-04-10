<?php

namespace App\Http\Controllers;

use App\Actions\Attachment\AttachFileAction;
use App\Actions\Expense\SyncExpensePaymentsAction;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Models\ExpenseStatus;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Throwable;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expenses = Expense::query()
            ->with(['attachments', 'costCenter', 'payee', 'status', 'category'])
            ->filter($request->all())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('expenses/Index', [
            'expenses' => ExpenseResource::collection($expenses),
            'statuses' => ExpenseStatus::select('id as value', 'name as label', 'color')->get(),
            'search_by' => $request->search_by,
            'status' => $request->status,
        ]);
    }

    public function paymentOptions(Payment $payment): JsonResponse
    {
        $expensesEligible = Expense::query()
            ->where('expense_status_id', '=', ExpenseStatus::DONE)
            ->where('amount', '=', $payment->amount)
            ->whereDoesntHave('payments')
            ->orWhere('id', $payment->expense_id)
            ->get()
            ->map(fn ($expense) => [
                'value' => $expense->id,
                'label' => $expense->attachments->first()?->original_name,
            ]);
        return response()->json($expensesEligible);
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request, AttachFileAction $action): RedirectResponse
    {
        $request->validate([
            'attachment' => ['required', 'file', 'image', 'max:5120'],
        ]);

        DB::transaction(function () use ($request, $action) {
            $expense = Expense::create();
            $action->execute(
                $expense,
                $request->file('attachment'),
                'attachments/expenses');

            return $expense;
        });

        return back()->with(['success' => 'Despesa anexado com sucesso']);
    }

    /**
     * @throws Throwable
     */
    public function update(
        UpdateExpenseRequest $request,
        Expense $expense,
        SyncExpensePaymentsAction $syncPayments
    ) {
        DB::transaction(function () use ($request, $expense, $syncPayments) {
            $expense->update($request->validated());
            if ($request->has('payments')) {
                $syncPayments->execute(
                    $expense,
                    $request->input('payments', [])
                );
            }
        });

        return back()->with('success', 'Despesa atualizada com sucesso');
    }

    public function uploadAttachment(Request $request, Expense $expense, AttachFileAction $attachFile)
    {
        $request->validate([
            'attachment' => ['required', 'file', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $expense->attachments()->each(function ($attachment) {
            Storage::delete($attachment->file_path);
            $attachment->delete();
        });

        $attachFile->execute(
            $expense,
            $request->file('attachment'),
            'payments'
        );

        return back()->with('success', 'Arquivo atualizado com sucesso');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back()->with('success', 'Despesa apagada com sucesso!');
    }
}
