<?php

use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\CostCenterController;
use App\Http\Controllers\CostCenterTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseStatusController;
use App\Http\Controllers\PayeeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentStatusController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\WorkController;
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
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('bank-accounts', BankAccountController::class)
        ->only(['index']);

    Route::resource('payments', PaymentController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::post('/payments/{payment}/attachments', [PaymentController::class, 'uploadAttachment'])
        ->name('payments.attachment');

    Route::resource('payment-statuses', PaymentStatusController::class)
        ->only(['index', 'store']);
    Route::resource('payment-types', PaymentTypeController::class)
        ->only(['index']);

    Route::resource('expenses', ExpenseController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('/expenses/{payment}/payment-options', [ExpenseController::class, 'paymentOptions'])
        ->name('expenses.payment-options');
    Route::resource('expense-statuses', ExpenseStatusController::class)
        ->only(['index', 'store']);

    Route::resource('works', WorkController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('works/options', [WorkController::class, 'options'])
        ->name('works.options');

    Route::resource('cost-centers', CostCenterController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('/cost-centers/options', [CostCenterController::class, 'options'])
        ->name('expenses.payment-options');
    Route::resource('cost-center-types', CostCenterTypeController::class)
        ->only(['index', 'store']);

    Route::resource('payees', PayeeController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('payees/options', [PayeeController::class, 'options'])
        ->name('payees.options');
});

require __DIR__.'/settings.php';
