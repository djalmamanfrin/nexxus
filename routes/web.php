<?php

use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\CostCenterController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseStatusController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentStatusController;
use App\Http\Controllers\PaymentTypeController;
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

    Route::resource('bank-accounts', BankAccountController::class)
        ->only(['index']);

    Route::resource('payments', PaymentController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::post(
        '/payments/{payment}/attachments',
        [PaymentController::class, 'uploadAttachment']
    )->name('payments.attachment');

    Route::get(
        '/payments/{payment}/available-expenses',
        [PaymentController::class, 'availableExpenses']
    )->name('payments.available-expenses');

    Route::resource('payment-statuses', PaymentStatusController::class)
        ->only(['index', 'store']);
    Route::resource('payment-types', PaymentTypeController::class)
        ->only(['index']);

    Route::resource('expenses', ExpenseController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('expense-statuses', ExpenseStatusController::class)
        ->only(['index', 'store']);
    Route::resource('cost-centers', CostCenterController::class)
        ->only(['index', 'store']);
});

require __DIR__.'/settings.php';
