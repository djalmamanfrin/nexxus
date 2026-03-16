<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Task;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expenses = Expense::when(
            $request->filled('notes'),
            fn($query) => $query->whereLike('notes', '%' . $request->notes . '%')
        )
        ->when(
            $request->filled('status'),
            fn($query) => $query->where('status', '<=', Carbon::parse($request->status))
        )
        ->when(
            $request->filled('paid_at'),
            fn($query) => $query->where('paid_at', '>=', Carbon::parse($request->paid_at))
        )
        ->when(
            $request->filled('created_at'),
            fn($query) => $query->where('created_at', '<=', Carbon::parse($request->created_at))
        )
        ->orderBy('created_at', 'ASC')
        ->paginate(10)
        ->withQueryString();

        return Inertia::render('expenses/Index', [
            'expenses' => $expenses,
            'search_by' => $request->search_by,
            'status' => $request->status,
            'paid_at' => $request->paid_at,
            'created_to' => $request->created_to,
        ]);
    }

    // Visualizar os detalhes da tarefa
    public function show(Task $task)
    {

        // Enviar os dados diretamente para a view
        return Inertia::render('expenses/Show', ['task' => $task]);
    }

    // Carregar o formulário cadastrar tarefa
    public function create()
    {
        return Inertia::render('expenses/Create');
    }

    // Cadastrar o tarefa no banco de dados
    public function store(Request $request)
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
            $task = Task::create([
                'name' => $request->name,
                'started_at' => $request->started_at,
                'finished_at' => $request->finished_at,
            ]);

            return redirect()->route('expenses.show', ['task' => $task->id])->with('success', 'Tarefa cadastrada com sucesso!');
        } catch (Exception $e) {

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Tarefa não cadastrada!');
        }
    }

    // Carregar o formulário editar tarefa
    public function edit(Task $task)
    {

        return Inertia::render('expenses/Edit', ['task' => $task]);
    }

    // Editar a tarefa no banco de dados
    public function update(Task $task, Request $request)
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

            $task->update([
                'name' => $request->name,
                'started_at' => $request->started_at,
                'finished_at' => $request->finished_at,
            ]);

            return redirect()->route('expenses.show', ['task' => $task->id])->with('success', 'Tarefa editada com sucesso!');
        } catch (Exception $e) {

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Tarefa não editada!');
        }
    }

    // Apagar a tarefa
    public function destroy(Task $task)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            $task->delete();

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('expenses.index')->with('success', 'Tarefa apagada com sucesso!');
        } catch (Exception $e) {

            // Redirecionar o usuário, enviar a mensagem de erro
            return redirect()->route('expenses.index')->with('error', 'Tarefa não apagada!');
        }
    }
}
