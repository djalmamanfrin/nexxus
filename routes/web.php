<?php

use App\Http\Controllers\Expenses\ExpenseController;
use App\Http\Controllers\Tasks\TaskController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// Grupo de rotas restritas
Route::group(['middleware' => 'auth', 'verified'], function () {

    // Dashboard
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Despesas
    Route::prefix('expenses')->group(function () {
        Route::get('/', [ExpenseController::class, 'index'])->name('expense.index');
        Route::get('/create', [ExpenseController::class, 'create'])->name('expense.create');
        Route::post('/', [ExpenseController::class, 'store'])->name('expense.store');
        Route::get('/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
        Route::get('/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
        Route::put('/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
        Route::delete('/{task}', [ExpenseController::class, 'destroy'])->name('expense.destroy');
    });

    // Tarefas
    Route::prefix('tasks')->group(function () {

        // Listar
        Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

        // Cadastrar
        Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/', [TaskController::class, 'store'])->name('tasks.store');

        // Visualizar
        Route::get('/{task}', [TaskController::class, 'show'])->name('tasks.show');

        // Editar
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
        Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');

        // Apagar
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    });
});

require __DIR__.'/settings.php';
