<?php

namespace App\Http\Controllers;

use App\Actions\Attachment\AttachFileAction;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Models\ExpenseStatus;
use App\Models\Payment;
use App\Support\Logger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

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
            ->where(function ($query) use ($payment) {
                $query
                    ->whereDoesntHave('payments')
                    ->orWhere('id', $payment->expense_id);
            })
            ->get()
            ->map(fn ($expense) => [
                'value' => $expense->id,
                'label' => $expense->attachments->first()?->original_name,
            ]);
        return response()->json($expensesEligible);
    }

    public function show(Expense $expense)
    {
        return Inertia::render('expenses/Show', ['expense' => $expense]);
    }

    public function store(Request $request, AttachFileAction $action): JsonResponse
    {
        $request->validate([
            'attachment' => ['required', 'file', 'image', 'max:5120'],
        ]);

        $attachment = null;
        $expense = DB::transaction(function () use ($request, $action, &$attachment) {
            $expense = Expense::create();
            $attachment = $action->execute(
                $expense,
                $request->file('attachment'),
                'attachments/expenses');

            return $expense;
        });

        return response()->json([
            'field' => 'expense_id',
            'value' => $expense->id,
            'label' => $attachment->original_name,
        ], Response::HTTP_CREATED);
    }

    public function update(UpdateExpenseRequest  $request, Expense $expense)
    {
        $validated = $request->validated();
        $expense->update($validated);

        return back()->with('success', 'Atualizado com sucesso');
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
        return redirect()
            ->route('expenses.index')
            ->with('success', 'Despesa apagada com sucesso!');
    }
}
