<?php

namespace App\Http\Controllers;

use App\Actions\Attachment\AttachFileAction;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseStatus;
use App\Support\Logger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expenses = Expense::query()
            ->with('attachments')
            ->filter($request->all())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('expenses/Index', [
            'expenses' => $expenses,
            'statuses' => ExpenseStatus::select('id as value', 'name as label', 'color')->get(),
            'search_by' => $request->search_by,
            'status' => $request->status,
        ]);
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

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()
            ->route('expenses.index')
            ->with('success', 'Despesa apagada com sucesso!');
    }
}
