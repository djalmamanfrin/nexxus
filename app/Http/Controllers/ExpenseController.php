<?php

namespace App\Http\Controllers;

use App\Actions\Attachment\AttachFileAction;
use App\Models\Expense;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expenses = Expense::filter($request->all())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('expenses/Index', [
            'expenses' => $expenses,
            'search_by' => $request->search_by,
        ]);
    }

    // Visualizar os detalhes da tarefa
    public function show(Expense $expense)
    {

        // Enviar os dados diretamente para a view
        return Inertia::render('expenses/Show', ['expense' => $expense]);
    }

    // Carregar o formulário cadastrar tarefa
    public function create()
    {
        return Inertia::render('expenses/Create');
    }

    /**
     * @throws Throwable
     */
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

    // Carregar o formulário editar tarefa
    public function edit(Expense $expense)
    {

        return Inertia::render('expenses/Edit', ['expense' => $expense]);
    }

    // Editar a tarefa no banco de dados
    public function update(Expense $expense, Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'started_at' => 'required|date',
                'finished_at' => 'required|date|after_or_equal:started_at',
            ],
            [
                'name.required' => "Campo nome é obrigatório!",

                'started_at.required' => "Campo data/hora de início é obrigatório!",
                'started_at.date' => "Informe uma data/hora válida para o início!",

                'finished_at.required' => "Campo data/hora de término é obrigatório!",
                'finished_at.date' => "Informe uma data/hora válida para o término!",
                'finished_at.after_or_equal' => "A data de término deve ser igual ou posterior à data de início!",
            ]
        );

        // Capturar possíveis exceções durante a execução.
        try {

            $expense->update([
                'name' => $request->name,
                'started_at' => $request->started_at,
                'finished_at' => $request->finished_at,
            ]);

            return redirect()->route('expenses.show', ['expense' => $expense->id])->with('success', 'Tarefa editada com sucesso!');
        } catch (Exception $e) {

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Tarefa não editada!');
        }
    }

    // Apagar a tarefa
    public function destroy(Expense $expense)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            $expense->delete();

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('expenses.index')->with('success', 'Tarefa apagada com sucesso!');
        } catch (Exception $e) {

            // Redirecionar o usuário, enviar a mensagem de erro
            return redirect()->route('expenses.index')->with('error', 'Tarefa não apagada!');
        }
    }
}
